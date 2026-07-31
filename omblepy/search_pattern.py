import sys
import glob

# Pola yang kita cari: (sistol, diastol, nadi) yang sudah dikonfirmasi dari layar alat
KNOWN_READINGS = [
    (125, 66, 85),   # Memori 13 di alat
    (116, 77, 85),   # Memori 14 di alat
]

def search_pattern(filename, sys_, dia, bpm):
    with open(filename, "rb") as f:
        data = f.read()

    pattern = bytes([sys_, dia, bpm])
    found = []
    start = 0
    while True:
        idx = data.find(pattern, start)
        if idx == -1:
            break
        found.append(idx)
        start = idx + 1
    return found


def main():
    files = sys.argv[1:]
    if not files:
        # kalau tidak dikasih argumen, cari semua file .bin di folder ini
        files = glob.glob("*.bin")

    if not files:
        print("Tidak ada file .bin ditemukan. Jalankan dengan argumen nama file,")
        print("contoh: python search_pattern.py before.bin after.bin before2.bin ...")
        return

    print(f"Mencari di {len(files)} file: {files}\n")

    for filename in files:
        try:
            for sys_, dia, bpm in KNOWN_READINGS:
                positions = search_pattern(filename, sys_, dia, bpm)
                for pos in positions:
                    print(f"[{filename}] Pola ({sys_},{dia},{bpm}) ditemukan di offset {hex(pos)} (desimal {pos})")
        except FileNotFoundError:
            print(f"[!] File {filename} tidak ditemukan, dilewati.")


if __name__ == "__main__":
    main()