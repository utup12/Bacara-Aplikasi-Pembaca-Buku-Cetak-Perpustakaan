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

## APK Android

Proyek ini dapat dibangun menjadi APK tanpa Unity. Tampilan web Bacara tetap digunakan, tetapi tombol **Pindai halaman** pada APK membuka pemindai Android: foto resolusi penuh diambil dengan CameraX lalu teks dikenali secara lokal oleh ML Kit. Jalur ini lebih akurat daripada Tesseract.js browser untuk teks buku.

### Membuat dan menguji APK di komputer

1. Instal Android Studio dengan **Android SDK Platform 36**, Android SDK Build-Tools, dan Android SDK Platform-Tools. JDK 21 dari Android Studio sudah cukup.
2. Jalankan di akar repositori:

   ```powershell
   npm ci
   npm run mobile:sync
   ```

3. Buka folder `android` menggunakan Android Studio, tunggu Gradle Sync selesai, lalu tekan **Run** untuk memasang ke ponsel.
4. Untuk uji pada ponsel fisik, aktifkan *Developer options* dan **USB debugging**, hubungkan kabel USB, lalu setujui permintaan otorisasi di ponsel.

APK debug juga dibuat otomatis oleh workflow GitHub **Build Android APK** setiap ada perubahan terkait Android. Buka tab **Actions**, pilih workflow yang sukses, lalu unduh artefak `Bacara-debug-APK`. APK ini untuk pengujian, bukan untuk Play Store.

Sebelum merilis ke Play Store, ubah `appId` bila diperlukan, buat keystore rilis pribadi, dan hasilkan berkas `.aab` yang ditandatangani. Jangan pernah mengunggah keystore atau kata sandinya ke repository.

## Privasi

Gambar halaman buku dan hasil OCR diproses di browser. Aplikasi tidak menyimpan foto atau teks ke database.
