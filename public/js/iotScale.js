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

    // Hasil tinggi akan dimasukkan ke sini
    const heightInput = document.getElementById("birth_height");

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

            // Tampilkan popup pilihan Bluetooth
            bluetoothDevice = await navigator.bluetooth.requestDevice({
                filters: [
                    {
                        name: "Alat_Balita_Posyandu",
                    },
                ],

                optionalServices: [SERVICE_UUID],
            });

            statusText.textContent = "Menghubungkan...";

            // Event apabila Bluetooth terputus
            bluetoothDevice.addEventListener(
                "gattserverdisconnected",
                handleDisconnected,
            );

            // Hubungkan GATT
            const server = await bluetoothDevice.gatt.connect();

            // Ambil service
            const service = await server.getPrimaryService(SERVICE_UUID);

            // Ambil characteristic
            bluetoothCharacteristic =
                await service.getCharacteristic(CHARACTERISTIC_UUID);

            // Aktifkan notifikasi
            await bluetoothCharacteristic.startNotifications();

            // Dengarkan data dari ESP32
            bluetoothCharacteristic.addEventListener(
                "characteristicvaluechanged",
                receiveData,
            );

            // ======================================
            // TAMPILAN JIKA BERHASIL TERHUBUNG
            // ======================================
            statusText.textContent = "Alat terhubung";

            statusText.className = "text-sm font-medium text-green-600";

            connectButton.classList.add("hidden");

            measureButton.classList.remove("hidden");

            disconnectButton.classList.remove("hidden");

            console.log("BLE berhasil terhubung");
        } catch (error) {
            console.error("Gagal terhubung:", error);

            statusText.textContent = "Tidak ada perangkat Bluetooth yang dipilih.";

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
            // Nonaktifkan tombol sementara
            measureButton.disabled = true;

            measureButton.textContent = "Mengukur...";

            measureButton.classList.add("opacity-50", "cursor-not-allowed");

            statusText.textContent = "Sedang mengambil data...";

            statusText.className = "text-sm text-blue-600";

            // ======================================
            // KIRIM PERINTAH KE ESP32
            // ======================================
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

            // Parse JSON
            const data = JSON.parse(dataString);

            // ======================================
            // JIKA ADA DATA TINGGI
            // ======================================
            if (data.tinggi_badan !== undefined) {
                const tinggi = parseFloat(data.tinggi_badan);

                if (!isNaN(tinggi)) {
                    // Masukkan hasil ke input
                    heightInput.value = tinggi.toFixed(2);

                    statusText.textContent = `Berhasil: ${tinggi.toFixed(2)} cm`;

                    statusText.className = "text-sm font-medium text-green-600";

                    console.log("Tinggi:", tinggi, "cm");
                }
            }

            // ======================================
            // JIKA ESP32 MENGIRIM ERROR
            // ======================================
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
