document.addEventListener('DOMContentLoaded', () => {

    const COLOR_CLASSES = {
        gray:   'bg-gray-100 text-gray-500',
        blue:   'bg-blue-100 text-blue-700',
        green:  'bg-green-100 text-green-700',
        yellow: 'bg-yellow-100 text-yellow-700',
        orange: 'bg-orange-100 text-orange-700',
        red:    'bg-red-100 text-red-700',
    };


    // ============================================================
    // KLASIFIKASI SUHU
    // ============================================================

    function classifySuhu(rawValue) {

        if (rawValue === null || rawValue === '') {
            return null;
        }

        const value = parseFloat(rawValue);

        if (isNaN(value)) {
            return null;
        }

        if (value < 35.0) {
            return {
                text: 'Suhu Rendah',
                color: 'blue'
            };
        }

        if (value <= 37.5) {
            return {
                text: 'Normal',
                color: 'green'
            };
        }

        return {
            text: 'Demam',
            color: 'red'
        };
    }


    // ============================================================
    // KLASIFIKASI TEKANAN DARAH
    //
    // Mengikuti rentang yang digunakan Yuwell:
    //
    // Hipotensi:
    // SYS < 90 ATAU DIA < 60
    //
    // Normal:
    // SYS 90-139 DAN DIA 60-89
    //
    // Hipertensi Tingkat 1:
    // SYS 140-159 ATAU DIA 90-99
    //
    // Hipertensi Tingkat 2:
    // SYS 160-179 ATAU DIA 100-109
    //
    // Hipertensi Tingkat 3:
    // SYS >= 180 ATAU DIA >= 110
    // ============================================================

    function classifyTensi(rawSys, rawDia) {

        if (
            rawSys === null ||
            rawSys === '' ||
            rawDia === null ||
            rawDia === ''
        ) {
            return null;
        }

        const sys = parseFloat(rawSys);
        const dia = parseFloat(rawDia);

        if (isNaN(sys) || isNaN(dia)) {
            return null;
        }


        // --------------------------------------------------------
        // HIPOTENSI
        // --------------------------------------------------------

        if (sys < 90 || dia < 60) {
            return {
                text: 'Hipotensi',
                color: 'blue'
            };
        }


        // --------------------------------------------------------
        // HIPERTENSI TINGKAT 3
        // --------------------------------------------------------

        if (sys >= 180 || dia >= 110) {
            return {
                text: 'Hipertensi Tingkat 3',
                color: 'red'
            };
        }


        // --------------------------------------------------------
        // HIPERTENSI TINGKAT 2
        // --------------------------------------------------------

        if (sys >= 160 || dia >= 100) {
            return {
                text: 'Hipertensi Tingkat 2',
                color: 'red'
            };
        }


        // --------------------------------------------------------
        // HIPERTENSI TINGKAT 1
        // --------------------------------------------------------

        if (sys >= 140 || dia >= 90) {
            return {
                text: 'Hipertensi Tingkat 1',
                color: 'orange'
            };
        }


        // --------------------------------------------------------
        // NORMAL
        //
        // Karena semua kondisi hipertensi dan hipotensi sudah
        // ditangani di atas, nilai yang tersisa berada pada:
        //
        // SYS 90-139
        // DIA 60-89
        // --------------------------------------------------------

        return {
            text: 'Normal',
            color: 'green'
        };
    }


    // ============================================================
    // RENDER BADGE
    // ============================================================

    function renderBadge(badgeEl, result) {

        if (!badgeEl) {
            return;
        }

        if (!result) {
            badgeEl.textContent = '';
            badgeEl.className = 'hidden';
            return;
        }

        badgeEl.textContent = result.text;

        badgeEl.className =
            'inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full ' +
            (COLOR_CLASSES[result.color] || COLOR_CLASSES.gray);
    }


    // ============================================================
    // SUHU
    // ============================================================

    const suhuInput = document.getElementById('temperature');
    const suhuBadge = document.getElementById('suhuStatusBadge');

    if (suhuInput && suhuBadge) {

        const updateSuhu = () => {
            renderBadge(
                suhuBadge,
                classifySuhu(suhuInput.value)
            );
        };

        suhuInput.addEventListener('input', updateSuhu);
        suhuInput.addEventListener('change', updateSuhu);

        updateSuhu();
    }


    // ============================================================
    // TENSI
    // ============================================================

    const sysInput = document.getElementById('systolic_pressure');
    const diaInput = document.getElementById('diastolic_pressure');
    const tensiBadge = document.getElementById('tensiStatusBadge');

    if (sysInput && diaInput && tensiBadge) {

        const updateTensi = () => {

            renderBadge(
                tensiBadge,
                classifyTensi(
                    sysInput.value,
                    diaInput.value
                )
            );

        };

        sysInput.addEventListener('input', updateTensi);
        sysInput.addEventListener('change', updateTensi);

        diaInput.addEventListener('input', updateTensi);
        diaInput.addEventListener('change', updateTensi);

        updateTensi();
    }

});