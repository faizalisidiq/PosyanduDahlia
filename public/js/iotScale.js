document.addEventListener("DOMContentLoaded", function () {
    let bluetoothDevice = null;
    let bleCharacteristic = null;
    let receivedDataString = ""; // Buffer untuk menampung data yang terpotong

    const connectScaleButton = document.getElementById("connectiotScaleButton");
    const disconnectScaleButton = document.getElementById(
        "disconnectiotScaleButton",
    );
    const scaleStatus = document.getElementById("iotscaleStatus");

    const birthHeightInput =
        document.getElementById("birth_height") ||
        document.getElementById("height");
    const birthWeightInput =
        document.getElementById("birth_weight") ||
        document.getElementById("weight");

    // UUID Standar untuk BLE ESP32 (Harus sama dengan yang di kode ESP32)
    const bleServiceUUID = "4fafc201-1fb5-459e-8fcc-c5c9c331914b";
    const bleCharacteristicUUID = "beb5483e-36e1-4688-b7f5-ea07361b26a8";

    if (!connectScaleButton || !disconnectScaleButton) {
        console.error("Tombol koneksi tidak ditemukan");
        return;
    }

    function setScaleStatus(message, type = "default") {
        if (!scaleStatus) return;
        scaleStatus.className = "text-sm mt-2 font-medium";

        if (type === "success") {
            scaleStatus.classList.add("text-teal-600");
        } else if (type === "warning") {
            scaleStatus.classList.add("text-yellow-600");
        } else if (type === "error") {
            scaleStatus.classList.add("text-red-600");
        } else {
            scaleStatus.classList.add("text-gray-500");
        }
        scaleStatus.textContent = message;
    }

    function setButtonState(isConnected) {
        if (isConnected) {
            connectScaleButton.classList.add("hidden");
            connectScaleButton.style.display = "none";
            disconnectScaleButton.classList.remove("hidden");
            disconnectScaleButton.style.display = "inline-block";
        } else {
            disconnectScaleButton.classList.add("hidden");
            disconnectScaleButton.style.display = "none";
            connectScaleButton.classList.remove("hidden");
            connectScaleButton.style.display = "inline-block";
        }
    }

    // Fungsi untuk menangani data yang masuk dari Bluetooth ESP32
    function handleNotifications(event) {
        let value = event.target.value;
        let decoder = new TextDecoder("utf-8");
        receivedDataString += decoder.decode(value);

        // Tunggu sampai menerima karakter '}' yang menandakan JSON selesai
        if (receivedDataString.includes("}")) {
            try {
                // Ekstrak bagian JSON saja (menghindari noise karakter aneh)
                let startIndex = receivedDataString.indexOf("{");
                let endIndex = receivedDataString.lastIndexOf("}");

                if (startIndex !== -1 && endIndex !== -1) {
                    let jsonStr = receivedDataString.substring(
                        startIndex,
                        endIndex + 1,
                    );
                    const data = JSON.parse(jsonStr);

                    // Update Form Input UI
                    if (birthHeightInput && data.tinggi_badan !== undefined) {
                        birthHeightInput.value = Number(
                            data.tinggi_badan,
                        ).toFixed(2);
                    }
                    if (birthWeightInput && data.berat_badan !== undefined) {
                        birthWeightInput.value = Number(
                            data.berat_badan,
                        ).toFixed(2);
                    }

                    setScaleStatus(
                        `Bluetooth Terhubung | TB: ${data.tinggi_badan} cm | BB: ${data.berat_badan} kg`,
                        "success",
                    );
                }
            } catch (error) {
                console.error("Gagal parsing JSON dari Bluetooth:", error);
            }
            // Kosongkan buffer setelah data diproses
            receivedDataString = "";
        }
    }

    // Fungsi Utama untuk Menghubungkan Bluetooth
    async function connectBluetoothScale() {
        try {
            setScaleStatus("Mencari perangkat Bluetooth...", "warning");

            // 1. Meminta browser mencari berdasarkan Service UUID (Bukan Nama)
            bluetoothDevice = await navigator.bluetooth.requestDevice({
                filters: [{ services: [bleServiceUUID] }],
            });

            setScaleStatus(
                "Menghubungkan ke " + bluetoothDevice.name + "...",
                "warning",
            );

            // Event listener jika terputus tiba-tiba
            bluetoothDevice.addEventListener(
                "gattserverdisconnected",
                disconnectBluetoothScale,
            );

            // 2. Konek ke GATT Server
            const server = await bluetoothDevice.gatt.connect();

            // 3. Dapatkan Service
            const service = await server.getPrimaryService(bleServiceUUID);

            // 4. Dapatkan Characteristic
            bleCharacteristic = await service.getCharacteristic(
                bleCharacteristicUUID,
            );

            // 5. Mulai menerima notifikasi (data stream)
            await bleCharacteristic.startNotifications();
            bleCharacteristic.addEventListener(
                "characteristicvaluechanged",
                handleNotifications,
            );

            setButtonState(true);
            setScaleStatus(
                "Bluetooth Berhasil Terhubung. Menunggu data...",
                "success",
            );
        } catch (error) {
            console.error("Koneksi Bluetooth Gagal:", error);
            setScaleStatus(
                "Gagal terhubung ke Bluetooth. Pastikan alat menyala.",
                "error",
            );
            setButtonState(false);
        }
    }

    // Fungsi untuk memutuskan koneksi
    function disconnectBluetoothScale() {
        if (bluetoothDevice && bluetoothDevice.gatt.connected) {
            bluetoothDevice.gatt.disconnect();
        }
        setButtonState(false);
        setScaleStatus("Timbangan Bluetooth Terputus", "default");
    }

    connectScaleButton.addEventListener("click", connectBluetoothScale);
    disconnectScaleButton.addEventListener("click", disconnectBluetoothScale);
});
