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

- Kamera meminta resolusi hingga 2560×1440 jika perangkat mendukungnya.
- Bingkai pemindaian membantu pengguna menempatkan satu halaman buku; OCR hanya membaca area tersebut.
- Pemindaian otomatis menunggu tiga sampel frame yang stabil dan cukup tajam sebelum menjalankan OCR. Tombol **Pindai halaman** memilih foto resolusi penuh bila browser Android mendukungnya; bila tidak, aplikasi membuka kamera bawaan untuk mengambil foto berkualitas lebih tinggi.
- Gambar dikonversi ke grayscale, dinormalisasi berdasarkan pencahayaan frame, dan diberi peningkatan kontras ringan sebelum dikirim ke OCR. Untuk foto resolusi penuh, aplikasi juga membandingkan hasil dengan versi threshold adaptif yang membantu mengatasi bayangan pada kertas.
- Hasil ber-confidence rendah tidak menggantikan teks yang sudah lebih baik, sehingga teks tidak mudah berubah menjadi hasil yang berantakan.

## Privasi

Gambar halaman buku dan hasil OCR diproses di browser. Aplikasi tidak menyimpan foto atau teks ke database.
