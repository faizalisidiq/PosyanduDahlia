document.addEventListener('DOMContentLoaded', () => {
    let bluetoothDevice = null;
    let bluetoothCharacteristic = null;

    const connectButton = document.getElementById('connectScaleButton');
    const disconnectButton = document.getElementById('disconnectScaleButton');

    const statusElement = document.getElementById('scaleStatus');
    const measurementInfo = document.getElementById('measurementInfo');

    const weightInput = document.getElementById('birth_weight');
    const heightInput = document.getElementById('birth_height');

    const SERVICE_UUID = '0000fff0-0000-1000-8000-00805f9b34fb';
    const CHARACTERISTIC_UUID = '0000fff1-0000-1000-8000-00805f9b34fb';

    let lastWeightKg = null;
    let lastHeightCm = null;
    let stableCounter = 0;

    /*
     * Jika elemen alat timbang tidak ada pada halaman ini,
     * script berhenti tanpa menyebabkan error.
     */
    if (!connectButton || !disconnectButton || !weightInput || !heightInput) {
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

    function updateMeasurementInfo(weightKg, heightCm, isStable = false) {
        if (!measurementInfo) {
            return;
        }

        const weightText = weightKg !== null
            ? `${weightKg.toFixed(3).replace('.', ',')} kg`
            : '-';

        const heightText = heightCm !== null
            ? `${heightCm.toFixed(1).replace('.', ',')} cm`
            : '-';

        measurementInfo.textContent = isStable
            ? `Data stabil: BB ${weightText}, PB ${heightText}`
            : `Membaca alat: BB ${weightText}, PB ${heightText}`;
    }

    function parseMeasurement(bytes) {
        if (bytes.length !== 13) {
            return null;
        }

        if (bytes[0] !== 0xFF || bytes[1] !== 0xA5) {
            return null;
        }

        /*
         * Struktur paket SENSSUN GROWTH:
         *
         * bytes[2]-bytes[3] = berat dalam gram, big-endian
         * Contoh: 00 4B = 75 gram = 0,075 kg
         *
         * bytes[6]-bytes[7] = panjang badan dalam 0,1 cm, big-endian
         * Contoh: 01 F4 = 500 = 50,0 cm
         */
        const rawWeightGram = (bytes[2] << 8) | bytes[3];
        const rawHeightTenthCm = (bytes[6] << 8) | bytes[7];

        return {
            weightKg: rawWeightGram > 0 ? rawWeightGram / 1000 : null,
            heightCm: rawHeightTenthCm > 0 ? rawHeightTenthCm / 10 : null,
            rawWeightGram,
            rawHeightTenthCm
        };
    }

    function handleNotification(event) {
        const value = event.target.value;

        const bytes = new Uint8Array(
            value.buffer,
            value.byteOffset,
            value.byteLength
        );

        const measurement = parseMeasurement(bytes);

        if (!measurement) {
            return;
        }

        const { weightKg, heightCm } = measurement;

        if (weightKg !== null) {
            weightInput.value = weightKg.toFixed(3);
            weightInput.dispatchEvent(new Event('input', { bubbles: true }));
            weightInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (heightCm !== null) {
            heightInput.value = heightCm.toFixed(1);
            heightInput.dispatchEvent(new Event('input', { bubbles: true }));
            heightInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        const sameWeight = weightKg === lastWeightKg;
        const sameHeight = heightCm === lastHeightCm;

        if (sameWeight && sameHeight) {
            stableCounter++;
        } else {
            stableCounter = 0;
        }

        lastWeightKg = weightKg;
        lastHeightCm = heightCm;

        const isStable = stableCounter >= 10;

        updateMeasurementInfo(weightKg, heightCm, isStable);

        if (isStable) {
            setStatus('Timbangan terhubung. Data pengukuran stabil.', 'green');
        } else {
            setStatus('Timbangan terhubung. Membaca data...', 'blue');
        }

        console.log('Data timbangan:', {
            weightKg,
            heightCm,
            rawPacket: Array.from(bytes)
                .map(byte => byte.toString(16).padStart(2, '0').toUpperCase())
                .join('-')
        });
    }

    function handleDisconnected() {
        bluetoothDevice = null;
        bluetoothCharacteristic = null;

        connectButton.classList.remove('hidden');
        disconnectButton.classList.add('hidden');

        setStatus('Koneksi timbangan terputus.', 'red');

        if (measurementInfo) {
            measurementInfo.textContent =
                'Timbangan terputus. Hubungkan ulang untuk melanjutkan pengukuran.';
        }
    }

    async function connectScale() {
        if (!navigator.bluetooth) {
            setStatus('Browser ini tidak mendukung Bluetooth Web.', 'red');

            alert(
                'Gunakan Google Chrome atau Microsoft Edge. ' +
                'Pastikan Bluetooth perangkat aktif.'
            );

            return;
        }

        try {
            setStatus('Mencari timbangan...', 'blue');

            bluetoothDevice = await navigator.bluetooth.requestDevice({
                filters: [
                    {
                        namePrefix: 'SENSSUN'
                    }
                ],
                optionalServices: [SERVICE_UUID]
            });

            bluetoothDevice.addEventListener(
                'gattserverdisconnected',
                handleDisconnected
            );

            setStatus(
                `Menghubungkan ke ${bluetoothDevice.name || 'timbangan'}...`,
                'blue'
            );

            const server = await bluetoothDevice.gatt.connect();

            const service = await server.getPrimaryService(SERVICE_UUID);

            bluetoothCharacteristic = await service.getCharacteristic(
                CHARACTERISTIC_UUID
            );

            await bluetoothCharacteristic.startNotifications();

            bluetoothCharacteristic.addEventListener(
                'characteristicvaluechanged',
                handleNotification
            );

            connectButton.classList.add('hidden');
            disconnectButton.classList.remove('hidden');

            setStatus(
                `Terhubung ke ${bluetoothDevice.name || 'SENSSUN GROWTH'}.`,
                'green'
            );

            if (measurementInfo) {
                measurementInfo.textContent =
                    'Silakan timbang dan ukur bayi. Nilai akan masuk otomatis.';
            }
        } catch (error) {
            console.error(error);

            if (error.name === 'NotFoundError') {
                setStatus('Tidak ada perangkat Bluetooth yang dipilih.', 'yellow');
                return;
            }

            setStatus(`Gagal terhubung: ${error.message}`, 'red');
        }
    }

    function disconnectScale() {
        if (bluetoothDevice?.gatt?.connected) {
            bluetoothDevice.gatt.disconnect();
            return;
        }

        handleDisconnected();
    }

    connectButton.addEventListener('click', connectScale);
    disconnectButton.addEventListener('click', disconnectScale);
});
