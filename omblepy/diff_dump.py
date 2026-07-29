import sys

def main():
    if len(sys.argv) != 3:
        print("Cara pakai: python diff_dump.py before.bin after.bin")
        return

    with open(sys.argv[1], "rb") as f:
        before = f.read()

    with open(sys.argv[2], "rb") as f:
        after = f.read()

    if len(before) != len(after):
        print(f"[!] Ukuran file beda: before={len(before)} byte, after={len(after)} byte")
        print("    (tetap lanjut membandingkan sepanjang bagian yang sama)")

    length = min(len(before), len(after))

    print(f"Membandingkan {length} byte...\n")

    changedRegions = []
    inRegion = False
    regionStart = None

    for i in range(length):
        if before[i] != after[i]:
            if not inRegion:
                inRegion = True
                regionStart = i
        else:
            if inRegion:
                inRegion = False
                changedRegions.append((regionStart, i))

    if inRegion:
        changedRegions.append((regionStart, length))

    if not changedRegions:
        print("TIDAK ADA PERUBAHAN SAMA SEKALI antara dua file ini.")
        print("Ini berarti alamat yang di-dump belum mencakup lokasi penyimpanan record.")
        print("Coba perlebar rentang alamat saat menjalankan dump_eeprom.py")
        return

    print(f"Ditemukan {len(changedRegions)} area yang berubah:\n")

    for start, end in changedRegions:
        print(f"=== Alamat {hex(start)} - {hex(end)} ({end - start} byte) ===")
        print(f"  SEBELUM : {before[start:end].hex()}")
        print(f"  SESUDAH : {after[start:end].hex()}")
        print()


if __name__ == "__main__":
    main()