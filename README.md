# Bacara

Bacara membantu pengguna membaca halaman buku cetak melalui kamera. Aplikasi memindai teks Bahasa Indonesia di perangkat menggunakan Tesseract.js dan membacakannya dengan Text-to-Speech browser.

## Akses dari perangkat apa pun

Setiap push ke branch `main` otomatis menerbitkan versi statis melalui GitHub Pages. Setelah workflow pertama selesai dan GitHub Pages diaktifkan dengan sumber **GitHub Actions**, aplikasi tersedia di:

`https://utup12.github.io/Bacara-Aplikasi-Pembaca-Buku-Cetak-Perpustakaan/`

Gunakan HTTPS agar browser dapat meminta izin kamera. Pada ponsel, buka URL tersebut lalu izinkan akses kamera ketika diminta.

## Menjalankan secara lokal

```powershell
composer install
php artisan serve
```

Kemudian buka `http://127.0.0.1:8000`.

## Optimasi kamera

- Pratinjau kamera memakai target 1280×720 pada 24 FPS.
- OCR memproses area pemindaian di tengah, bukan keseluruhan frame.
- Pemindaian otomatis menunggu OCR sebelumnya selesai supaya penggunaan CPU tidak menumpuk.

## Privasi

Gambar halaman buku dan hasil OCR diproses di browser. Aplikasi tidak menyimpan foto atau teks ke database.
