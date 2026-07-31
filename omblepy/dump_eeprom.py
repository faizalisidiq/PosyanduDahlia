import asyncio
import sys
import bleak

# Ganti MAC ini kalau device kalian beda (cek dari output omblepy sebelumnya)
DEVICE_MAC = "C8:32:EA:6B:BE:2A"

RX_UUID = "49123040-aee8-11e1-a74d-0002a5d5c51b"
TX_UUID = "db5b55e0-aee7-11e1-965e-0002a5d5c51b"

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
        print("  [!] CRC error pada paket, diabaikan")
        return

    rxPacketType = combinedRawRx[1:3]
    rxEepromAddress = combinedRawRx[3:5]
    expectedNumDataBytes = combinedRawRx[5]

    if expectedNumDataBytes > (len(combinedRawRx) - 8):
        rxDataBytes = bytes(b'\xff') * expectedNumDataBytes
    else:
        if rxPacketType == bytearray.fromhex("8f00"):
            rxDataBytes = combinedRawRx[6:7]
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
        raise ValueError(
            f"Alamat balasan {rxEepromAddress.hex()} tidak sesuai "
            f"permintaan {address.to_bytes(2, 'big').hex()}"
        )

    return rxDataBytes


async def main():
    global client

    outFile = sys.argv[1] if len(sys.argv) > 1 else "dump.bin"
    startAddr = int(sys.argv[2], 16) if len(sys.argv) > 2 else 0x0000
    endAddr = int(sys.argv[3], 16) if len(sys.argv) > 3 else 0x2000

    print(f"Menghubungkan ke {DEVICE_MAC}...")
    client = bleak.BleakClient(DEVICE_MAC)
    await client.connect()
    print("Terhubung. Memulai transmisi...")

    await client.start_notify(RX_UUID, callback)

    await sendAndWait(bytearray.fromhex("0800000000100018"))
    if rxPacketType != bytearray.fromhex("8000"):
        raise ValueError("Gagal memulai transmisi data")

    print(f"Membaca EEPROM dari {hex(startAddr)} sampai {hex(endAddr)}...")

    allData = bytearray()
    blockSize = 0x38
    addr = startAddr

    while addr < endAddr:
        size = min(blockSize, endAddr - addr)
        data = await readBlock(addr, size)
        allData += data
        addr += size
        print(f"  dibaca sampai {hex(addr)} / {hex(endAddr)}", end="\r")

    print()

    await sendAndWait(bytearray.fromhex("080f000000000007"))

    await client.stop_notify(RX_UUID)
    await client.disconnect()

    with open(outFile, "wb") as f:
        f.write(allData)

    print(f"Selesai. {len(allData)} byte disimpan ke {outFile}")


asyncio.run(main())