document.addEventListener('DOMContentLoaded', () => {
    let bluetoothDevice = null;
    let bluetoothCharacteristic = null;

    const connectButton = document.getElementById('connectThermometerButton');
    const disconnectButton = document.getElementById('disconnectThermometerButton');
    const statusElement = document.getElementById('thermometerStatus');
    const temperatureInput = document.getElementById('temperature');

    const SERVICE_UUID = 'health_thermometer';
    const CHARACTERISTIC_UUID = 'temperature_measurement';

    /*
     * Jika elemen termometer tidak ada pada halaman ini,
     * script berhenti tanpa menyebabkan error.
     */
    if (!connectButton || !disconnectButton || !temperatureInput) {
        return;
    }

    function setStatus(message, color = 'gray') {
        if (!statusElement) {
            return;
        }

        const colorClasses = {
            gray: 'text-gray-500',
            blue: 'text-blue-600',
            green: 'text-green-600',
            red: 'text-red-600',
            yellow: 'text-yellow-600'
        };

        statusElement.className = `text-sm ${colorClasses[color] || colorClasses.gray}`;
        statusElement.textContent = message;
    }

    /*
     * Parsing sesuai spesifikasi Bluetooth SIG:
     * Health Thermometer Service (0x1809)
     * Characteristic: Temperature Measurement (0x2A1C)
     *
     * Format: flags (1 byte) + nilai suhu IEEE-11073 32-bit FLOAT (4 byte)
     */
    function parseTemperatureMeasurement(dataView) {
        const flags = dataView.getUint8(0);
        const isFahrenheit = (flags & 0x01) !== 0;

        let mantissa = dataView.getUint8(1) | (dataView.getUint8(2) << 8) | (dataView.getUint8(3) << 16);
        if (mantissa >= 0x800000) mantissa -= 0x1000000;
        const exponent = dataView.getInt8(4);
        const value = mantissa * Math.pow(10, exponent);

        return { value, unit: isFahrenheit ? 'F' : 'C' };
    }

    function handleNotification(event) {
        const result = parseTemperatureMeasurement(event.target.value);

        temperatureInput.value = result.value.toFixed(1);
        temperatureInput.dispatchEvent(new Event('input', { bubbles: true }));
        temperatureInput.dispatchEvent(new Event('change', { bubbles: true }));

        setStatus(`Terhubung. Suhu terbaca: ${result.value.toFixed(1)}°${result.unit}`, 'green');

        console.log('Data termometer:', result);
    }

    function handleDisconnected() {
        bluetoothDevice = null;
        bluetoothCharacteristic = null;

        connectButton.classList.remove('hidden');
        disconnectButton.classList.add('hidden');

        setStatus('Koneksi termometer terputus.', 'red');
    }

    async function connectThermometer() {
        if (!navigator.bluetooth) {
            setStatus('Browser ini tidak mendukung Bluetooth Web.', 'red');

            alert(
                'Gunakan Google Chrome atau Microsoft Edge. ' +
                'Pastikan Bluetooth perangkat aktif.'
            );

            return;
        }

        try {
            setStatus('Mencari termometer...', 'blue');

            bluetoothDevice = await navigator.bluetooth.requestDevice({
                filters: [{ services: [SERVICE_UUID] }]
            });

            bluetoothDevice.addEventListener('gattserverdisconnected', handleDisconnected);

            setStatus(`Menghubungkan ke ${bluetoothDevice.name || 'termometer'}...`, 'blue');

            const server = await bluetoothDevice.gatt.connect();
            const service = await server.getPrimaryService(SERVICE_UUID);

            bluetoothCharacteristic = await service.getCharacteristic(CHARACTERISTIC_UUID);

            await bluetoothCharacteristic.startNotifications();
            bluetoothCharacteristic.addEventListener('characteristicvaluechanged', handleNotification);

            connectButton.classList.add('hidden');
            disconnectButton.classList.remove('hidden');

            setStatus(`Terhubung ke ${bluetoothDevice.name || 'termometer'}. Silakan ukur suhu.`, 'green');
        } catch (error) {
            console.error(error);

            if (error.name === 'NotFoundError') {
                setStatus('Tidak ada perangkat Bluetooth yang dipilih.', 'yellow');
                return;
            }

            setStatus(`Gagal terhubung: ${error.message}`, 'red');
        }
    }

    function disconnectThermometer() {
        if (bluetoothDevice?.gatt?.connected) {
            bluetoothDevice.gatt.disconnect();
            return;
        }

        handleDisconnected();
    }

    connectButton.addEventListener('click', connectThermometer);
    disconnectButton.addEventListener('click', disconnectThermometer);
});