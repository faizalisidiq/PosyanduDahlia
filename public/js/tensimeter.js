/**
 * ============================================================
 * YUWELL BP-YE670CR - WEB BLUETOOTH
 * ============================================================
 *
 * Browser
 *   ↓
 * Web Bluetooth
 *   ↓
 * Yuwell BP-YE670CR
 *   ↓
 * Form Laravel
 *
 * UUID:
 *
 * Blood Pressure Service
 * 00001810-0000-1000-8000-00805f9b34fb
 *
 * Blood Pressure Measurement
 * 00002a35-0000-1000-8000-00805f9b34fb
 *
 * Intermediate Cuff Pressure
 * 00002a36-0000-1000-8000-00805f9b34fb
 * ============================================================
 */

(() => {

    "use strict";


    // =========================================================
    // UUID YUWELL YE670CR
    // =========================================================

    const BP_SERVICE_UUID =
        "00001810-0000-1000-8000-00805f9b34fb";

    const BP_MEASUREMENT_UUID =
        "00002a35-0000-1000-8000-00805f9b34fb";

    const CUFF_PRESSURE_UUID =
        "00002a36-0000-1000-8000-00805f9b34fb";


    // =========================================================
    // VARIABEL GLOBAL
    // =========================================================

    let yuwellDevice = null;

    let yuwellServer = null;

    let bpMeasurementCharacteristic = null;

    let cuffPressureCharacteristic = null;

    let connected = false;


    // =========================================================
    // HELPER GET ELEMENT
    // =========================================================

    function getElement(id) {

        return document.getElementById(id);

    }


    // =========================================================
    // AMBIL ELEMENT HTML
    // =========================================================

    const connectButton =
        getElement("connectYuwellButton");

    const disconnectButton =
        getElement("disconnectYuwellButton");

    const statusElement =
        getElement("tensiStatus");

    /*
     * Tekanan manset sebenarnya tidak perlu
     * ditampilkan pada tampilan baru.
     *
     * Tetapi tetap kita dukung kalau element-nya
     * masih ada di Blade.
     */

    const cuffPressureElement =
        getElement("cuffPressure");


    // =========================================================
    // FIELD HASIL PENGUKURAN
    // =========================================================

    const systolicElement =
        getElement("systolic_pressure");

    const diastolicElement =
        getElement("diastolic_pressure");

    const pulseElement =
        getElement("pulse");


    // =========================================================
    // CEK WEB BLUETOOTH
    // =========================================================

    function isBluetoothSupported() {

        if (!navigator.bluetooth) {

            console.error(
                "[YUWELL] Web Bluetooth tidak didukung browser."
            );

            setStatus(
                "Browser tidak mendukung Web Bluetooth.",
                "error"
            );

            if (connectButton) {

                connectButton.disabled = true;

            }

            return false;
        }

        return true;
    }


    // =========================================================
    // STATUS UI
    // =========================================================

    function setStatus(
        message,
        type = "normal"
    ) {

        console.log(
            "[YUWELL]",
            message
        );


        if (!statusElement) {

            return;

        }


        statusElement.textContent =
            message;


        statusElement.classList.remove(
            "text-gray-500",
            "text-green-600",
            "text-red-600",
            "text-yellow-600"
        );


        if (type === "success") {

            statusElement.classList.add(
                "text-green-600"
            );

        }

        else if (type === "error") {

            statusElement.classList.add(
                "text-red-600"
            );

        }

        else if (type === "warning") {

            statusElement.classList.add(
                "text-yellow-600"
            );

        }

        else {

            statusElement.classList.add(
                "text-gray-500"
            );

        }

    }


    // =========================================================
    // DATA VIEW → HEX
    // =========================================================

    function dataViewToHex(dataView) {

        const bytes =
            new Uint8Array(
                dataView.buffer,
                dataView.byteOffset,
                dataView.byteLength
            );


        return Array
            .from(bytes)
            .map(
                byte =>
                    byte
                        .toString(16)
                        .padStart(2, "0")
                        .toUpperCase()
            )
            .join(" ");

    }


    // =========================================================
    // DATA VIEW → DECIMAL
    // =========================================================

    function dataViewToDecimal(dataView) {

        const bytes =
            new Uint8Array(
                dataView.buffer,
                dataView.byteOffset,
                dataView.byteLength
            );


        return Array
            .from(bytes)
            .join(" ");

    }


    // =========================================================
    // PARSE BLOOD PRESSURE 2A35
    // =========================================================
    //
    // Contoh data Yuwell:
    //
    // 1E 73 00 59 00 61 00 E6 07 08 1E 05 21 2E 4C 00 00 00 00
    //
    // DECIMAL:
    //
    // 30 115 0 89 0 97 0 230 7 8 30 5 33 46 76 ...
    //
    // Hasil:
    //
    // SYS   = 115
    // DIA   = 89
    // MAP   = 97
    // PULSE = 76
    //
    // =========================================================

    function parseBloodPressure(dataView) {

        if (
            !dataView ||
            dataView.byteLength < 7
        ) {

            console.warn(
                "[YUWELL] Data 2A35 terlalu pendek."
            );

            return null;
        }


        const bytes =
            new Uint8Array(
                dataView.buffer,
                dataView.byteOffset,
                dataView.byteLength
            );


        // -----------------------------------------------------
        // FLAGS
        // -----------------------------------------------------

        const flags =
            bytes[0];


        // -----------------------------------------------------
        // UNIT
        //
        // 0 = mmHg
        // 1 = kPa
        // -----------------------------------------------------

        const unitKpa =
            (flags & 0x01) !== 0;


        // -----------------------------------------------------
        // SYS
        // BYTE 1-2
        // -----------------------------------------------------

        let offset = 1;


        const sysRaw =
            dataView.getUint16(
                offset,
                true
            );


        offset += 2;


        // -----------------------------------------------------
        // DIA
        // BYTE 3-4
        // -----------------------------------------------------

        const diaRaw =
            dataView.getUint16(
                offset,
                true
            );


        offset += 2;


        // -----------------------------------------------------
        // MAP
        // BYTE 5-6
        // -----------------------------------------------------

        const mapRaw =
            dataView.getUint16(
                offset,
                true
            );


        offset += 2;


        let sys =
            sysRaw;

        let dia =
            diaRaw;

        let map =
            mapRaw;


        // -----------------------------------------------------
        // KONVERSI kPa → mmHg
        // -----------------------------------------------------

        if (unitKpa) {

            sys *= 7.50062;

            dia *= 7.50062;

            map *= 7.50062;

        }


        // -----------------------------------------------------
        // PULSE
        //
        // Struktur yang kita temukan:
        //
        // 0     FLAGS
        // 1-2   SYS
        // 3-4   DIA
        // 5-6   MAP
        // 7-8   YEAR
        // 9     MONTH
        // 10    DAY
        // 11    HOUR
        // 12    MINUTE
        // 13    SECOND
        // 14    PULSE
        // -----------------------------------------------------

        let pulse = null;


        if (bytes.length > 14) {

            pulse =
                bytes[14];

        }


        // -----------------------------------------------------
        // WAKTU
        // -----------------------------------------------------

        let measurementDate = null;


        if (bytes.length >= 14) {

            const year =
                dataView.getUint16(
                    7,
                    true
                );

            const month =
                bytes[9];

            const day =
                bytes[10];

            const hour =
                bytes[11];

            const minute =
                bytes[12];

            const second =
                bytes[13];


            measurementDate = {

                year,

                month,

                day,

                hour,

                minute,

                second

            };

        }


        // -----------------------------------------------------
        // RETURN
        // -----------------------------------------------------

        return {

            flags,

            unitKpa,

            systolic:
                Math.round(sys),

            diastolic:
                Math.round(dia),

            map:
                Math.round(map),

            pulse:
                pulse !== null
                    ? Math.round(pulse)
                    : null,

            date:
                measurementDate,

            hex:
                dataViewToHex(dataView)

        };

    }


    // =========================================================
    // PARSE INTERMEDIATE CUFF PRESSURE 2A36
    // =========================================================

    function parseCuffPressure(dataView) {

        if (
            !dataView ||
            dataView.byteLength < 3
        ) {

            return null;

        }


        const bytes =
            new Uint8Array(
                dataView.buffer,
                dataView.byteOffset,
                dataView.byteLength
            );


        const flags =
            bytes[0];


        let pressure =
            dataView.getUint16(
                1,
                true
            );


        const unitKpa =
            (flags & 0x01) !== 0;


        if (unitKpa) {

            pressure *= 7.50062;

        }


        return {

            pressure:
                Math.round(pressure),

            unitKpa,

            hex:
                dataViewToHex(dataView)

        };

    }


    // =========================================================
    // UPDATE FIELD FORM
    // =========================================================

    function updateBloodPressureForm(result) {

        if (!result) {

            return;

        }


        // -----------------------------------------------------
        // SISTOL
        // -----------------------------------------------------

        if (systolicElement) {

            systolicElement.value =
                result.systolic;

        }


        // -----------------------------------------------------
        // DIASTOL
        // -----------------------------------------------------

        if (diastolicElement) {

            diastolicElement.value =
                result.diastolic;

        }


        // -----------------------------------------------------
        // NADI
        // -----------------------------------------------------

        if (
            pulseElement &&
            result.pulse !== null
        ) {

            pulseElement.value =
                result.pulse;

        }


        // -----------------------------------------------------
        // Trigger input + change
        // -----------------------------------------------------

        [
            systolicElement,
            diastolicElement,
            pulseElement

        ].forEach(
            element => {

                if (!element) {

                    return;

                }


                element.dispatchEvent(
                    new Event(
                        "input",
                        {
                            bubbles: true
                        }
                    )
                );


                element.dispatchEvent(
                    new Event(
                        "change",
                        {
                            bubbles: true
                        }
                    )
                );

            }
        );

    }


    // =========================================================
    // HANDLE BLOOD PRESSURE 2A35
    // =========================================================

    function handleBloodPressure(event) {

        try {

            const data =
                event.target.value;


            console.log(
                "======================================"
            );

            console.log(
                "🩺 YUWELL BLOOD PRESSURE"
            );


            console.log(
                "HEX:",
                dataViewToHex(data)
            );


            console.log(
                "DECIMAL:",
                dataViewToDecimal(data)
            );


            const result =
                parseBloodPressure(data);


            if (!result) {

                return;

            }


            console.log(
                "SYS  =",
                result.systolic,
                "mmHg"
            );


            console.log(
                "DIA  =",
                result.diastolic,
                "mmHg"
            );


            console.log(
                "MAP  =",
                result.map,
                "mmHg"
            );


            console.log(
                "PULSE =",
                result.pulse,
                "BPM"
            );


            if (result.date) {

                console.log(
                    "WAKTU =",
                    `${result.date.year}-` +
                    `${String(
                        result.date.month
                    ).padStart(2, "0")}-` +
                    `${String(
                        result.date.day
                    ).padStart(2, "0")} ` +
                    `${String(
                        result.date.hour
                    ).padStart(2, "0")}:` +
                    `${String(
                        result.date.minute
                    ).padStart(2, "0")}:` +
                    `${String(
                        result.date.second
                    ).padStart(2, "0")}`
                );

            }


            // -------------------------------------------------
            // Masukkan hasil ke form
            // -------------------------------------------------

            updateBloodPressureForm(
                result
            );


            // -------------------------------------------------
            // Update status
            // -------------------------------------------------

            setStatus(

                `Hasil: ` +
                `${result.systolic}/` +
                `${result.diastolic} mmHg | ` +
                `Nadi ` +
                `${result.pulse ?? "-"} BPM`,

                "success"

            );


            console.log(
                "======================================"
            );


        }

        catch (error) {

            console.error(
                "[YUWELL] Gagal memproses 2A35:",
                error
            );

        }

    }


    // =========================================================
    // HANDLE CUFF PRESSURE 2A36
    // =========================================================

    function handleCuffPressure(event) {

        try {

            const data =
                event.target.value;


            const result =
                parseCuffPressure(data);


            if (!result) {

                return;

            }


            console.log(
                "📈 INTERMEDIATE CUFF PRESSURE"
            );


            console.log(
                "HEX:",
                result.hex
            );


            console.log(
                "Cuff Pressure =",
                result.pressure,
                "mmHg"
            );


            /*
             * Tidak wajib ditampilkan.
             *
             * Kalau element cuffPressure masih ada,
             * tetap akan di-update.
             */

            if (cuffPressureElement) {

                cuffPressureElement.textContent =
                    result.pressure;

            }

        }

        catch (error) {

            console.error(
                "[YUWELL] Gagal memproses 2A36:",
                error
            );

        }

    }


    // =========================================================
    // CONNECT YUWELL
    // =========================================================

    async function connectYuwell() {

        console.log(
            "[YUWELL] connectYuwell() dipanggil."
        );


        try {

            // -------------------------------------------------
            // CEK BLUETOOTH
            // -------------------------------------------------

            if (!isBluetoothSupported()) {

                return;

            }


            // -------------------------------------------------
            // STATUS
            // -------------------------------------------------

            setStatus(
                "Membuka Bluetooth chooser..."
            );


            console.log(
                "[YUWELL] Membuka Bluetooth chooser..."
            );


            // -------------------------------------------------
            // REQUEST DEVICE
            // -------------------------------------------------

            yuwellDevice =
                await navigator.bluetooth.requestDevice({

                    filters: [

                        {
                            name:
                                "Yuwell BP-YE670CR"
                        }

                    ],

                    optionalServices: [

                        BP_SERVICE_UUID

                    ]

                });


            console.log(
                "Perangkat dipilih!",
                "Nama:",
                yuwellDevice.name
            );


            console.log(
                "ID:",
                yuwellDevice.id
            );


            // -------------------------------------------------
            // EVENT DISCONNECT
            // -------------------------------------------------

            yuwellDevice.addEventListener(
                "gattserverdisconnected",
                handleDisconnect
            );


            setStatus(
                "Menghubungkan ke Yuwell..."
            );


            // -------------------------------------------------
            // CONNECT GATT
            // -------------------------------------------------

            yuwellServer =
                await yuwellDevice.gatt.connect();


            console.log(
                "GATT CONNECTED =",
                yuwellServer.connected
            );


            // -------------------------------------------------
            // CARI BLOOD PRESSURE SERVICE
            // -------------------------------------------------

            const service =
                await yuwellServer.getPrimaryService(
                    BP_SERVICE_UUID
                );


            console.log(
                "✓ Blood Pressure Service ditemukan"
            );


            // -------------------------------------------------
            // CARI 2A35
            // -------------------------------------------------

            bpMeasurementCharacteristic =
                await service.getCharacteristic(
                    BP_MEASUREMENT_UUID
                );


            console.log(
                "✓ 2A35 ditemukan"
            );


            // -------------------------------------------------
            // CARI 2A36
            // -------------------------------------------------

            cuffPressureCharacteristic =
                await service.getCharacteristic(
                    CUFF_PRESSURE_UUID
                );


            console.log(
                "✓ 2A36 ditemukan"
            );


            // -------------------------------------------------
            // LISTENER 2A35
            // -------------------------------------------------

            bpMeasurementCharacteristic.addEventListener(
                "characteristicvaluechanged",
                handleBloodPressure
            );


            // -------------------------------------------------
            // LISTENER 2A36
            // -------------------------------------------------

            cuffPressureCharacteristic.addEventListener(
                "characteristicvaluechanged",
                handleCuffPressure
            );


            // -------------------------------------------------
            // AKTIFKAN NOTIFICATION 2A35
            // -------------------------------------------------

            await bpMeasurementCharacteristic
                .startNotifications();


            console.log(
                "✓ Blood Pressure Measurement aktif"
            );


            // -------------------------------------------------
            // AKTIFKAN NOTIFICATION 2A36
            // -------------------------------------------------

            await cuffPressureCharacteristic
                .startNotifications();


            console.log(
                "✓ Intermediate Cuff Pressure aktif"
            );


            // -------------------------------------------------
            // UPDATE CONNECTED
            // -------------------------------------------------

            connected = true;


            // -------------------------------------------------
            // TOMBOL CONNECT
            // -------------------------------------------------

            if (connectButton) {

                connectButton.classList.add(
                    "hidden"
                );

            }


            // -------------------------------------------------
            // TOMBOL DISCONNECT
            // -------------------------------------------------

            if (disconnectButton) {

                disconnectButton.classList.remove(
                    "hidden"
                );

            }


            // -------------------------------------------------
            // STATUS SIAP
            // -------------------------------------------------

            setStatus(
                "Yuwell BP-YE670CR terhubung. Silakan lakukan pengukuran.",
                "success"
            );


            console.log(
                "======================================"
            );


            console.log(
                "🟢 SISTEM SIAP"
            );


            console.log(
                "Silakan lakukan pengukuran pada YE670CR."
            );


            console.log(
                "======================================"
            );

        }

        catch (error) {

            console.error(
                "[YUWELL] Connection error:",
                error
            );


            connected = false;


            // -------------------------------------------------
            // USER CANCEL
            // -------------------------------------------------

            if (
                error &&
                error.name === "NotFoundError"
            ) {

                setStatus(
                    "Pemilihan perangkat dibatalkan.",
                    "warning"
                );

            }

            else {

                setStatus(
                    "Gagal terhubung: " +
                    (
                        error.message ||
                        error
                    ),
                    "error"
                );

            }

        }

    }


    // =========================================================
    // DISCONNECT YUWELL
    // =========================================================

    async function disconnectYuwell() {

        console.log(
            "[YUWELL] disconnectYuwell() dipanggil."
        );


        try {

            if (
                yuwellDevice &&
                yuwellDevice.gatt &&
                yuwellDevice.gatt.connected
            ) {

                yuwellDevice.gatt.disconnect();

            }

        }

        catch (error) {

            console.error(
                "[YUWELL] Disconnect error:",
                error
            );

        }

        finally {

            handleDisconnect();

        }

    }


    // =========================================================
    // HANDLE DISCONNECT
    // =========================================================

    function handleDisconnect() {

        connected = false;


        bpMeasurementCharacteristic =
            null;


        cuffPressureCharacteristic =
            null;


        yuwellServer =
            null;


        // -----------------------------------------------------
        // TOMBOL CONNECT
        // -----------------------------------------------------

        if (connectButton) {

            connectButton.classList.remove(
                "hidden"
            );

        }


        // -----------------------------------------------------
        // TOMBOL DISCONNECT
        // -----------------------------------------------------

        if (disconnectButton) {

            disconnectButton.classList.add(
                "hidden"
            );

        }


        // -----------------------------------------------------
        // STATUS
        // -----------------------------------------------------

        setStatus(
            "Perangkat Yuwell terputus.",
            "warning"
        );


        console.log(
            "🔴 Yuwell terputus."
        );

    }


    // =========================================================
    // EVENT BUTTON
    // =========================================================
    //
    // MENGGUNAKAN EVENT DELEGATION
    //
    // Ini sengaja dibuat supaya tombol tetap bekerja
    // walaupun elemen Blade mengalami perubahan/render ulang.
    // =========================================================

    document.addEventListener(
        "click",
        function(event) {

            // -------------------------------------------------
            // TOMBOL HUBUNGKAN
            // -------------------------------------------------

            const connectTarget =
                event.target.closest(
                    "#connectYuwellButton"
                );


            if (connectTarget) {

                event.preventDefault();


                console.log(
                    "[YUWELL] Tombol Hubungkan Tensimeter diklik."
                );


                connectYuwell();


                return;

            }


            // -------------------------------------------------
            // TOMBOL PUTUSKAN
            // -------------------------------------------------

            const disconnectTarget =
                event.target.closest(
                    "#disconnectYuwellButton"
                );


            if (disconnectTarget) {

                event.preventDefault();


                console.log(
                    "[YUWELL] Tombol Putuskan Tensimeter diklik."
                );


                disconnectYuwell();

            }

        },
        true
    );


    // =========================================================
    // INIT
    // =========================================================

    function initializeYuwell() {

        console.log(
            "======================================"
        );


        console.log(
            "YUWELL BP-YE670CR WEB BLUETOOTH"
        );


        console.log(
            "======================================"
        );


        console.log(
            "Blood Pressure Service:",
            BP_SERVICE_UUID
        );


        console.log(
            "Measurement:",
            BP_MEASUREMENT_UUID
        );


        console.log(
            "Intermediate Cuff Pressure:",
            CUFF_PRESSURE_UUID
        );


        console.log(
            "Tombol connect:",
            connectButton
        );


        console.log(
            "Tombol disconnect:",
            disconnectButton
        );


        console.log(
            "Web Bluetooth:",
            !!navigator.bluetooth
        );


        isBluetoothSupported();


        console.log(
            "[YUWELL] Initialization selesai."
        );

    }


    // =========================================================
    // DOM READY
    // =========================================================

    if (
        document.readyState ===
        "loading"
    ) {

        document.addEventListener(
            "DOMContentLoaded",
            initializeYuwell
        );

    }

    else {

        initializeYuwell();

    }


    // =========================================================
    // GLOBAL DEBUG ACCESS
    // =========================================================

    window.YuwellTensimeter = {

        connect:
            connectYuwell,

        disconnect:
            disconnectYuwell,

        isConnected:
            () => connected,

        device:
            () => yuwellDevice,

        server:
            () => yuwellServer

    };


    // =========================================================
    // FINAL LOG
    // =========================================================

    console.log(
        "======================================"
    );


    console.log(
        "Yuwell BP-YE670CR Tensimeter JS loaded"
    );


    console.log(
        "Blood Pressure Service:",
        BP_SERVICE_UUID
    );


    console.log(
        "Measurement:",
        BP_MEASUREMENT_UUID
    );


    console.log(
        "======================================"
    );


})();