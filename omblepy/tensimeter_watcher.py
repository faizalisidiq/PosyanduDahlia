import asyncio
import struct
import datetime
import bleak
import urllib.request
import json
import os

# ==== KONFIGURASI ====
DEVICE_MAC = "C8:32:EA:6B:BE:2A"   # ganti kalau MAC alat kalian beda
LARAVEL_URL = "http://127.0.0.1:8000/api/vitals/tensi"
CEK_SETIAP_DETIK = 10               # jeda antar pengecekan
FILE_PENANDA_TERAKHIR = "last_sent_urutan.txt"

RX_UUID = "49123040-aee8-11e1-a74d-0002a5d5c51b"
TX_UUID = "db5b55e0-aee7-11e1-965e-0002a5d5c51b"

RECORD_BLOCKS = [
    (0x2e8, 56),
    (0x320, 56),
    (0x358, 56),
    (0x390, 28),
]

client = None
rxFinished = False
rxPacketType = None
rxEepromAddress = None
rxDataBytes = None


def callback(_, rxBytes):
    global rxFinished, rxPacketType, rxEepromAddress, rxDataBytes
    combinedRawRx = bytearray(rxBytes)
    xorCrc = 0
    for b in combinedRawRx:
        xorCrc ^= b
    if xorCrc:
        return
    rxPacketType = combinedRawRx[1:3]
    rxEepromAddress = combinedRawRx[3:5]
    expectedNumDataBytes = combinedRawRx[5]
    if expectedNumDataBytes > (len(combinedRawRx) - 8):
        rxDataBytes = bytes(b'\xff') * expectedNumDataBytes
    else:
        rxDataBytes = combinedRawRx[6:6 + expectedNumDataBytes]
    rxFinished = True


async def sendAndWait(command, timeout=3.0):
    global rxFinished
    rxFinished = False
    await client.write_gatt_char(TX_UUID, command, response=False)
    elapsed = 0
    while not rxFinished and elapsed < timeout:
        await asyncio.sleep(0.05)
        elapsed += 0.05
    if not rxFinished:
        raise TimeoutError("Tidak ada balasan dari alat (timeout)")


async def readBlock(address, blocksize):
    cmd = bytearray.fromhex("080100")
    cmd += address.to_bytes(2, 'big')
    cmd += blocksize.to_bytes(1, 'big')
    xorCrc = 0
    for b in cmd:
        xorCrc ^= b
    cmd += b'\x00'
    cmd.append(xorCrc)
    await sendAndWait(cmd)
    if rxEepromAddress != address.to_bytes(2, 'big'):
        raise ValueError("Alamat balasan tidak sesuai permintaan")
    return rxDataBytes


def decodeRecord(chunk):
    sistol = chunk[0] + 25
    diastol = chunk[1]
    nadi = chunk[2]
    seq = chunk[10]
    waktu = None
    try:
        year = 2000 + chunk[3]
        flags1 = chunk[4] | (chunk[5] << 8)
        flags2 = chunk[6] | (chunk[7] << 8)
        hour = flags1 & 0x1F
        day = (flags1 >> 5) & 0x1F
        month = (flags1 >> 10) & 0xF
        second = flags2 & 0x3F
        minute = (flags2 >> 6) & 0x3F
        waktu = datetime.datetime(year, month, day, hour, minute, second)
    except Exception:
        waktu = None
    return {"sistol": sistol, "diastol": diastol, "nadi": nadi, "urutan": seq, "waktu": waktu}


def kirimKeLaravel(reading):
    payload = {
        "systolic": reading["sistol"],
        "diastolic": reading["diastol"],
        "pulse": reading["nadi"],
        "measured_at": (
            reading["waktu"].isoformat() if reading["waktu"] else datetime.datetime.now().isoformat()
        ),
    }
    data = json.dumps(payload).encode("utf-8")
    req = urllib.request.Request(
        LARAVEL_URL, data=data,
        headers={"Content-Type": "application/json", "Accept": "application/json"},
        method="POST",
    )
    try:
        with urllib.request.urlopen(req, timeout=5) as resp:
            body = resp.read().decode("utf-8")
            print(f"  -> Terkirim ke Laravel: {body}")
            return True
    except Exception as e:
        print(f"  -> [!] Gagal kirim ke Laravel: {e}")
        return False


def bacaPenandaTerakhir():
    if os.path.exists(FILE_PENANDA_TERAKHIR):
        with open(FILE_PENANDA_TERAKHIR, "r") as f:
            try:
                return int(f.read().strip())
            except ValueError:
                return 0
    return 0


def simpanPenandaTerakhir(urutan):
    with open(FILE_PENANDA_TERAKHIR, "w") as f:
        f.write(str(urutan))


async def cekSekaliJalan():
    global client
    client = bleak.BleakClient(DEVICE_MAC)
    await client.connect()
    await client.start_notify(RX_UUID, callback)

    await sendAndWait(bytearray.fromhex("0800000000100018"))
    if rxPacketType != bytearray.fromhex("8000"):
        raise ValueError("Gagal memulai transmisi data")

    allData = bytearray()
    for addr, size in RECORD_BLOCKS:
        data = await readBlock(addr, size)
        allData += data

    await sendAndWait(bytearray.fromhex("080f000000000007"))
    await client.stop_notify(RX_UUID)
    await client.disconnect()

    recordSize = 14
    records = []
    for i in range(len(allData) // recordSize):
        chunk = allData[i*recordSize:(i+1)*recordSize]
        records.append(decodeRecord(chunk))

    return max(records, key=lambda r: r["urutan"])


async def main():
    print("=== Tensimeter Watcher ===")
    print(f"Cek otomatis setiap {CEK_SETIAP_DETIK} detik. Tekan Ctrl+C untuk berhenti.\n")

    penandaTerakhir = bacaPenandaTerakhir()
    print(f"Penanda record terakhir yang sudah dikirim: {penandaTerakhir}")

    while True:
        waktuSekarang = datetime.datetime.now().strftime("%H:%M:%S")
        try:
            terbaru = await cekSekaliJalan()

            if terbaru["urutan"] > penandaTerakhir:
                print(f"[{waktuSekarang}] Record BARU ditemukan: "
                      f"{terbaru['sistol']}/{terbaru['diastol']} mmHg, nadi={terbaru['nadi']} "
                      f"(urutan={terbaru['urutan']})")
                berhasil = kirimKeLaravel(terbaru)
                if berhasil:
                    penandaTerakhir = terbaru["urutan"]
                    simpanPenandaTerakhir(penandaTerakhir)
            else:
                print(f"[{waktuSekarang}] Tidak ada record baru (masih di urutan {penandaTerakhir})")

        except Exception as e:
            print(f"[{waktuSekarang}] [!] Gagal cek alat: {e}")
            print("    (alat mungkin tidak dalam jangkauan / mati, akan dicoba lagi)")

        await asyncio.sleep(CEK_SETIAP_DETIK)


if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        print("\nDihentikan oleh pengguna.")