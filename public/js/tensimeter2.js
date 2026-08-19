// =======================================================
// Tensimeter BLE
// Versi mengikuti thermometer.js
// =======================================================

let tensiDevice = null;
let tensiServer = null;
let tensiService = null;
let tensiCharacteristic = null;

let tensiConnected = false;

// UUID HARUS sama dengan ESP32
const SERVICE_UUID = "7b8c0001-8f1d-4a71-b111-111111111111";
const CHARACTERISTIC_UUID = "7b8c0002-8f1d-4a71-b111-111111111111";

async function connectTensimeter() {

    try {

        if (tensiConnected) {
            disconnectTensimeter();
            return;
        }

        tensiDevice =
        await navigator.bluetooth.requestDevice({

            filters: [
                {
                    name: "ESP32_Tensimeter"
                }
            ],

            optionalServices: [
                SERVICE_UUID
            ]

        });

        tensiServer =
        await tensiDevice.gatt.connect();

        tensiService =
        await tensiServer.getPrimaryService(
            SERVICE_UUID
        );

        tensiCharacteristic =
        await tensiService.getCharacteristic(
            CHARACTERISTIC_UUID
        );

        await tensiCharacteristic.startNotifications();

        tensiCharacteristic.addEventListener(
            "characteristicvaluechanged",
            receiveTensiData
        );

        tensiDevice.addEventListener(
            "gattserverdisconnected",
            disconnectTensimeter
        );

        tensiConnected = true;

        updateTensiStatus(
            true,
            "Tensimeter berhasil terhubung."
        );

    }

    catch(err){

        console.log(err);

        updateTensiStatus(
            false,
            "Gagal menghubungkan tensimeter."
        );

    }

}

function disconnectTensimeter(){

    if(
        tensiDevice &&
        tensiDevice.gatt.connected
    ){

        tensiDevice.gatt.disconnect();

    }

    tensiConnected=false;

    updateTensiStatus(
        false,
        "Tensimeter diputus."
    );

}

function updateTensiStatus(
    connected,
    message
){

    const button =
    document.getElementById(
        "btnConnectTensimeter"
    );

    const status =
    document.getElementById(
        "statusTensimeter"
    );

    if(button){

        button.innerHTML =
        connected ?
        "Putuskan Tensimeter" :
        "Ambil dari Tensimeter";

    }

    if(status){

        status.innerHTML=message;

    }

}

// =======================================================
// Menerima data dari ESP32
// =======================================================

function receiveTensiData(event){

    try{

        const value =
        new TextDecoder().decode(event.target.value);

        console.log("BLE :", value);

        const data = JSON.parse(value);

        // =============================
        // STATUS ALAT
        // =============================

        if(data.type=="status"){

            const status =
            document.getElementById("statusTensimeter");

            if(status){

                status.innerHTML = data.value;

            }

            return;

        }

        // =============================
        // TEKANAN REALTIME
        // =============================

        if(data.type=="realtime"){

            const pressure =
            document.getElementById("pressureRealtime");

            if(pressure){

                pressure.innerHTML =
                data.pressure.toFixed(1)
                +" mmHg";

            }

            return;

        }

        // =============================
        // HASIL AKHIR
        // =============================

        if(data.type=="result"){

            isiHasilTensi(data);

            return;

        }

    }

    catch(err){

        console.log(err);

    }

}

function isiHasilTensi(data){

    const sistol =
    document.getElementById("systolic_pressure");

    if(sistol){
        sistol.value = data.sys;
    }

    const diastol =
    document.getElementById("diastolic_pressure");

    if(diastol){
        diastol.value = data.dia;
    }

    const pulse =
    document.getElementById("pulse");

    if(pulse){
        pulse.value = data.pulse;
    }

    updateTensiStatus(
        true,
        "✓ Data berhasil diambil"
    );
}