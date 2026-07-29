document.addEventListener('DOMContentLoaded', () => {
    const ambilButton = document.getElementById('ambilTensiButton');
    const statusElement = document.getElementById('tensiStatus');
    const systolicInput = document.getElementById('systolic_pressure');
    const diastolicInput = document.getElementById('diastolic_pressure');
    const pulseInput = document.getElementById('pulse');

    /*
     * Jika elemen tensimeter tidak ada pada halaman ini,
     * script berhenti tanpa menyebabkan error.
     */
    if (!systolicInput || !diastolicInput || !pulseInput) {
        return;
    }

    const POLLING_INTERVAL_MS = 5000;
    let lastMeasuredAt = null;
    let pollingTimer = null;

    function setStatus(message, color = 'gray') {
        if (!statusElement) {
            return;
        }

        const colorClasses = {
            gray: 'text-gray-500',
            blue: 'text-blue-600',
            green: 'text-green-600',
            red: 'text-red-600',
        };

        statusElement.className = `text-sm ${colorClasses[color] || colorClasses.gray}`;
        statusElement.textContent = message;
    }

    function fillInput(input, value) {
        input.value = value;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    async function cekData(isManual = false) {
        if (isManual) {
            setStatus('Mengambil data dari server...', 'blue');
        }

        try {
            const response = await fetch('/api/vitals/tensi/latest', {
                headers: { 'Accept': 'application/json' }
            });

            if (response.status === 404) {
                if (isManual) {
                    setStatus(
                        'Belum ada data. Ukur tensi lalu pastikan tensimeter_bridge.py / tensimeter_watcher.py berjalan.',
                        'red'
                    );
                }
                return;
            }

            const result = await response.json();

            if (!result.success) {
                if (isManual) {
                    setStatus('Gagal mengambil data dari server.', 'red');
                }
                return;
            }

            const data = result.data;

            // Kalau data ini sudah pernah diisi sebelumnya (waktu ukur sama), abaikan
            if (data.measured_at === lastMeasuredAt) {
                if (isManual) {
                    setStatus('Data masih sama seperti sebelumnya (belum ada pengukuran baru).', 'gray');
                }
                return;
            }

            lastMeasuredAt = data.measured_at;

            fillInput(systolicInput, data.systolic);
            fillInput(diastolicInput, data.diastolic);
            fillInput(pulseInput, data.pulse);

            const waktu = new Date(data.measured_at);
            const waktuStr = waktu.toLocaleString('id-ID');

            setStatus(`Data otomatis terisi (diukur: ${waktuStr})`, 'green');
        } catch (error) {
            console.error(error);
            if (isManual) {
                setStatus(`Gagal mengambil data: ${error.message}`, 'red');
            }
        }
    }

    function mulaiPolling() {
        cekData(false);
        pollingTimer = setInterval(() => cekData(false), POLLING_INTERVAL_MS);
    }

    function berhentiPolling() {
        if (pollingTimer) {
            clearInterval(pollingTimer);
        }
    }

    // Tombol manual tetap ada sebagai cadangan / pengecekan langsung
    if (ambilButton) {
        ambilButton.addEventListener('click', () => cekData(true));
    }

    setStatus('Menunggu data tensimeter secara otomatis...', 'gray');
    mulaiPolling();

    // Hentikan polling kalau halaman ditinggalkan, biar tidak boros request
    window.addEventListener('beforeunload', berhentiPolling);
});