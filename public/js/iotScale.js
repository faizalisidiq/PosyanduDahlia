document.addEventListener("DOMContentLoaded", () => {
    // ==========================================
    // UUID HARUS SAMA DENGAN PROGRAM ESP32
    // ==========================================
    const SERVICE_UUID = "4fafc201-1fb5-459e-8fcc-c5c9c331914b";
    const CHARACTERISTIC_UUID = "beb5483e-36e1-4688-b7f5-ea07361b26a8";

    // ==========================================
    // ELEMENT HTML
    // ==========================================
    const connectButton = document.getElementById("connectiotScaleButton");
    const measureButton = document.getElementById("measureiotScaleButton");
    const disconnectButton = document.getElementById(
        "disconnectiotScaleButton",
    );
    const statusText = document.getElementById("iotscaleStatus");

    // Hasil data akan dimasukkan ke sini
    const heightInput = document.getElementById("birth_height");
    // TAMBAHAN: Element untuk input berat badan (Sesuaikan ID-nya dengan HTML Anda)
    const weightInput = document.getElementById("birth_weight");

    // ==========================================
    // VARIABEL BLE
    // ==========================================
    let bluetoothDevice = null;
    let bluetoothCharacteristic = null;

    // ==========================================
    // HUBUNGKAN ALAT
    // ==========================================
    async function connectDevice() {
        try {
            statusText.textContent = "Mencari alat...";
            statusText.className = "text-sm text-blue-600";

            bluetoothDevice = await navigator.bluetooth.requestDevice({
                filters: [{ name: "Alat_Balita_Posyandu" }],
                optionalServices: [SERVICE_UUID],
            });

            statusText.textContent = "Menghubungkan...";
            bluetoothDevice.addEventListener(
                "gattserverdisconnected",
                handleDisconnected,
            );

            const server = await bluetoothDevice.gatt.connect();
            const service = await server.getPrimaryService(SERVICE_UUID);
            bluetoothCharacteristic =
                await service.getCharacteristic(CHARACTERISTIC_UUID);

            await bluetoothCharacteristic.startNotifications();
            bluetoothCharacteristic.addEventListener(
                "characteristicvaluechanged",
                receiveData,
            );

            statusText.textContent = "Alat terhubung";
            statusText.className = "text-sm font-medium text-green-600";
            connectButton.classList.add("hidden");
            measureButton.classList.remove("hidden");
            disconnectButton.classList.remove("hidden");

            console.log("BLE berhasil terhubung");
        } catch (error) {
            console.error("Gagal terhubung:", error);
            statusText.textContent =
                "Tidak ada perangkat Bluetooth yang dipilih.";
            statusText.className = "text-sm text-yellow-600";
        }
    }

    // ==========================================
    // TOMBOL AMBIL DATA
    // ==========================================
    async function measureHeight() {
        if (
            !bluetoothDevice ||
            !bluetoothDevice.gatt.connected ||
            !bluetoothCharacteristic
        ) {
            alert("Hubungkan alat terlebih dahulu.");
            return;
        }

        try {
            measureButton.disabled = true;
            measureButton.textContent = "Mengukur...";
            measureButton.classList.add("opacity-50", "cursor-not-allowed");

            // Beri tahu user bahwa proses ini butuh waktu beberapa detik
            statusText.textContent =
                "Sedang mengambil data Tinggi & Berat (Harap tunggu)...";
            statusText.className = "text-sm text-blue-600";

            const encoder = new TextEncoder();
            const command = encoder.encode("MEASURE");
            await bluetoothCharacteristic.writeValue(command);

            console.log("Perintah MEASURE dikirim ke ESP32");
        } catch (error) {
            console.error("Gagal mengambil data:", error);
            statusText.textContent = "Gagal mengambil data";
            statusText.className = "text-sm text-red-600";
            resetMeasureButton();
        }
    }

    // ==========================================
    // MENERIMA HASIL DARI ESP32
    // ==========================================
    function receiveData(event) {
        try {
            const value = event.target.value;
            const decoder = new TextDecoder("utf-8");
            const dataString = decoder.decode(value);

            console.log("Data dari ESP32:", dataString);

            // Parse JSON dari ESP32
            const data = JSON.parse(dataString);

            let statusMessage = "Berhasil: ";
            let isError = false;

            // ======================================
            // PROSES DATA TINGGI
            // ======================================
            if (data.tinggi_badan !== undefined && data.tinggi_badan !== null) {
                const tinggi = parseFloat(data.tinggi_badan);
                if (!isNaN(tinggi)) {
                    heightInput.value = tinggi.toFixed(2);
                    statusMessage += `Tinggi ${tinggi.toFixed(1)} cm | `;
                    console.log("Tinggi:", tinggi, "cm");
                }
            } else {
                statusMessage += "Tinggi (Gagal) | ";
                isError = true;
            }

            // ======================================
            // PROSES DATA BERAT (TAMBAHAN BARU)
            // ======================================
            if (
                data.berat_badan !== undefined &&
                data.berat_badan !== null &&
                data.berat_badan !== -1
            ) {
                const berat = parseFloat(data.berat_badan);
                if (!isNaN(berat)) {
                    // Masukkan ke element input berat badan
                    if (weightInput) {
                        weightInput.value = berat.toFixed(2);
                    }
                    statusMessage += `Berat ${berat.toFixed(2)} kg`;
                    console.log("Berat:", berat, "kg");
                }
            } else {
                statusMessage += "Berat (Gagal/Timbangan Mati)";
                isError = true;
                alert(
                    "Data berat badan gagal diambil! Pastikan timbangan menyala dan anak sudah berdiri di atasnya.",
                );
            }

            // Update UI Status
            statusText.textContent = statusMessage;
            statusText.className = isError
                ? "text-sm font-medium text-yellow-600"
                : "text-sm font-medium text-green-600";

            // Jika ESP32 mengirim error eksplisit
            if (data.error) {
                statusText.textContent = data.error;
                statusText.className = "text-sm text-red-600";
            }
        } catch (error) {
            console.error("Data BLE tidak valid:", error);
            statusText.textContent = "Data dari alat tidak valid";
            statusText.className = "text-sm text-red-600";
        }

        resetMeasureButton();
    }

    // ==========================================
    // RESET TOMBOL AMBIL DATA
    // ==========================================
    function resetMeasureButton() {
        measureButton.disabled = false;
        measureButton.textContent = "Ambil Data";
        measureButton.classList.remove("opacity-50", "cursor-not-allowed");
    }

    // ==========================================
    // PUTUSKAN BLUETOOTH
    // ==========================================
    function disconnectDevice() {
        if (bluetoothDevice && bluetoothDevice.gatt.connected) {
            bluetoothDevice.gatt.disconnect();
        }
    }

    // ==========================================
    // KETIKA BLUETOOTH TERPUTUS
    // ==========================================
    function handleDisconnected() {
        console.log("Bluetooth terputus");
        bluetoothCharacteristic = null;
        statusText.textContent = "Alat belum terhubung";
        statusText.className = "text-sm text-gray-500";

        connectButton.classList.remove("hidden");
        measureButton.classList.add("hidden");
        disconnectButton.classList.add("hidden");

        resetMeasureButton();
    }

    // ==========================================
    // EVENT BUTTON
    // ==========================================
    connectButton.addEventListener("click", connectDevice);
    measureButton.addEventListener("click", measureHeight);
    disconnectButton.addEventListener("click", disconnectDevice);
});
