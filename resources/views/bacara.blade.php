<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#123b32">
    <title>Bacara — Baca buku lewat kamera</title>
    <style>
        :root {
            --hijau: #0b4f43;
            --hijau-gelap: #07372f;
            --krem: #f7f4ec;
            --putih: #ffffff;
            --teks: #18201d;
            --abu: #52615b;
            --garis: #c8d1ca;
            --kuning: #f6c945;
            --merah: #b42318;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--krem);
            color: var(--teks);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            line-height: 1.55;
        }

        .skip-link {
            position: absolute;
            left: 12px;
            top: -60px;
            background: var(--kuning);
            color: #111;
            padding: 10px 16px;
            border-radius: 8px;
            z-index: 10;
            font-weight: 700;
        }

        .skip-link:focus { top: 12px; }

        header {
            background: var(--hijau-gelap);
            color: white;
            padding: 22px 20px;
        }

        .header-content, main {
            width: min(100%, 1100px);
            margin: 0 auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--kuning);
            color: #1a281e;
            font-size: 28px;
            font-weight: 800;
        }

        .brand h1 {
            margin: 0;
            font-size: clamp(1.65rem, 6vw, 2.25rem);
        }

        .brand p {
            margin: 0;
            color: #d9ebe3;
            font-size: 1rem;
        }

        main { padding: 28px 20px 48px; }

        .intro {
            max-width: 760px;
            margin-bottom: 24px;
        }

        .intro h2 { margin-bottom: 6px; }

        .privacy {
            border-left: 6px solid var(--hijau);
            background: #e6f1eb;
            padding: 14px 16px;
            border-radius: 8px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            margin-top: 24px;
        }

        .panel {
            background: var(--putih);
            border: 1px solid var(--garis);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(12, 48, 38, .08);
        }

        .panel h2 {
            margin: 0 0 8px;
            font-size: 1.35rem;
        }

        .panel p { color: var(--abu); }

        .camera {
            position: relative;
            overflow: hidden;
            min-height: 290px;
            background: #17201d;
            border-radius: 12px;
            margin: 18px 0;
        }

        video {
            display: block;
            width: 100%;
            min-height: 290px;
            object-fit: cover;
        }

        .scan-frame {
            position: absolute;
            inset: 10%;
            border: 3px solid var(--kuning);
            border-radius: 8px;
            pointer-events: none;
        }

        .scan-frame span {
            position: absolute;
            bottom: -38px;
            left: 50%;
            transform: translateX(-50%);
            width: max-content;
            color: white;
            font-size: .88rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        button {
            appearance: none;
            border: 2px solid var(--hijau);
            border-radius: 9px;
            min-height: 48px;
            padding: 10px 14px;
            background: white;
            color: var(--hijau-gelap);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover { background: #e6f1eb; }

        button.primary {
            background: var(--hijau);
            color: white;
        }

        button.primary:hover { background: var(--hijau-gelap); }

        button.warning {
            border-color: #8a3b00;
            color: #6d2e00;
        }

        button:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        button:focus-visible, textarea:focus-visible, input:focus-visible {
            outline: 4px solid #185adb;
            outline-offset: 3px;
        }

        #status {
            min-height: 52px;
            padding: 12px;
            border-radius: 8px;
            background: #eef2ef;
            color: #24352d;
            font-weight: 600;
        }

        #status[data-mode="error"] {
            color: #7c150f;
            background: #ffebe9;
        }

        textarea {
            width: 100%;
            min-height: 300px;
            resize: vertical;
            padding: 14px;
            border: 2px solid var(--garis);
            border-radius: 10px;
            color: var(--teks);
            background: #fff;
            font: inherit;
            line-height: 1.65;
        }

        label {
            display: block;
            margin: 16px 0 8px;
            font-weight: 700;
        }

        input[type="range"] {
            width: 100%;
            min-height: 36px;
            accent-color: var(--hijau);
        }

        .small {
            font-size: .92rem;
            color: var(--abu);
        }

        footer {
            text-align: center;
            padding: 18px;
            color: var(--abu);
        }

        @media (max-width: 760px) {
            body { font-size: 17px; }
            .grid { grid-template-columns: 1fr; }
            .panel { padding: 16px; }
            main { padding: 22px 14px 40px; }
            header { padding: 18px 14px; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#pemindai">Langsung ke pemindai</a>

    <header>
        <div class="header-content">
            <div class="brand">
                <div class="brand-icon" aria-hidden="true">B</div>
                <div>
                    <h1>Bacara</h1>
                    <p>Pembaca buku cetak melalui kamera dan suara</p>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="intro" aria-labelledby="judul-pengantar">
            <h2 id="judul-pengantar">Baca teks buku dengan kamera</h2>
            <p>Arahkan kamera ke halaman buku, pindai teks, lalu dengarkan hasilnya. Dirancang untuk penggunaan ponsel dan pembaca layar.</p>
            <p class="privacy"><strong>Privasi:</strong> gambar buku diproses langsung di browser. Bacara tidak menyimpan foto atau teks Anda ke database.</p>
        </section>

        <p id="status" role="status" aria-live="polite" data-mode="info">
            Tekan “Aktifkan kamera” untuk memulai.
        </p>

        <div class="grid">
            <section class="panel" id="pemindai" aria-labelledby="judul-kamera">
                <h2 id="judul-kamera">1. Pindai halaman</h2>
                <p>Pastikan teks terang, lurus, dan memenuhi kotak kuning.</p>

                <div class="camera">
                    <video id="video" autoplay playsinline muted aria-label="Pratinjau kamera buku"></video>
                    <div class="scan-frame" aria-hidden="true">
                        <span>Arahkan teks di dalam kotak</span>
                    </div>
                </div>

                <div class="actions">
                    <button class="primary" id="start-camera" type="button">Aktifkan kamera</button>
                    <button id="scan" type="button" disabled>Pindai teks sekarang</button>
                    <button id="auto-scan" type="button" disabled>Pindai otomatis: mati</button>
                    <button class="warning" id="stop-camera" type="button" disabled>Matikan kamera</button>
                </div>
            </section>

            <section class="panel" aria-labelledby="judul-teks">
                <h2 id="judul-teks">2. Dengarkan teks</h2>
                <p>Periksa atau perbaiki hasil pemindaian sebelum membacakannya.</p>

                <label for="hasil-teks">Hasil teks</label>
                <textarea id="hasil-teks" placeholder="Hasil OCR akan muncul di sini." aria-describedby="bantuan-teks"></textarea>
                <p class="small" id="bantuan-teks">Anda dapat mengedit teks dengan keyboard atau pembaca layar.</p>

                <label for="kecepatan">Kecepatan suara: <output id="nilai-kecepatan">0.9</output></label>
                <input id="kecepatan" type="range" min="0.6" max="1.3" step="0.1" value="0.9">

                <div class="actions">
                    <button class="primary" id="baca" type="button">Bacakan teks</button>
                    <button id="hentikan-suara" type="button">Hentikan suara</button>
                </div>
            </section>
        </div>
    </main>

    <footer>Bacara MVP · OCR Bahasa Indonesia dan Text-to-Speech di perangkat</footer>

    <canvas id="canvas" hidden></canvas>

    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@6/dist/tesseract.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const statusBox = document.getElementById('status');
        const startButton = document.getElementById('start-camera');
        const scanButton = document.getElementById('scan');
        const autoButton = document.getElementById('auto-scan');
        const stopButton = document.getElementById('stop-camera');
        const textArea = document.getElementById('hasil-teks');
        const speakButton = document.getElementById('baca');
        const stopSpeechButton = document.getElementById('hentikan-suara');
        const rate = document.getElementById('kecepatan');
        const rateValue = document.getElementById('nilai-kecepatan');

        let stream = null;
        let worker = null;
        let scanning = false;
        let autoTimer = null;
        let autoScanEnabled = false;

        function setStatus(message, mode = 'info') {
            statusBox.textContent = message;
            statusBox.dataset.mode = mode;
        }

        function refreshButtons() {
            const cameraOn = stream !== null;
            scanButton.disabled = !cameraOn || scanning;
            autoButton.disabled = !cameraOn;
            stopButton.disabled = !cameraOn;
            startButton.disabled = cameraOn;
        }

        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus('Kamera memerlukan browser modern dan alamat HTTPS atau localhost.', 'error');
                return;
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1280, max: 1280 },
                        height: { ideal: 720, max: 720 },
                        frameRate: { ideal: 24, max: 24 }
                    },
                    audio: false
                });

                video.srcObject = stream;
                await video.play();
                setStatus('Kamera aktif dalam mode hemat. Arahkan teks ke kotak kuning, lalu tekan “Pindai teks sekarang”.');
                refreshButtons();
            } catch (error) {
                setStatus('Kamera tidak dapat diakses. Izinkan akses kamera di browser, lalu coba lagi.', 'error');
                console.error(error);
            }
        }

        function stopAutomaticScan() {
            autoScanEnabled = false;

            if (autoTimer !== null) {
                window.clearTimeout(autoTimer);
                autoTimer = null;
            }
            autoButton.textContent = 'Pindai otomatis: mati';
        }

        function stopCamera() {
            stopAutomaticScan();

            if (stream) {
                stream.getTracks().forEach((track) => track.stop());
                stream = null;
            }

            video.srcObject = null;
            setStatus('Kamera dimatikan.');
            refreshButtons();
        }

        function captureFrame() {
            if (!video.videoWidth || !video.videoHeight) {
                throw new Error('Ukuran video belum tersedia.');
            }

            // Samakan area OCR dengan kotak kuning agar worker tidak membaca seluruh frame kamera.
            const inset = 0.10;
            const sourceX = Math.floor(video.videoWidth * inset);
            const sourceY = Math.floor(video.videoHeight * inset);
            const sourceWidth = Math.floor(video.videoWidth * (1 - (inset * 2)));
            const sourceHeight = Math.floor(video.videoHeight * (1 - (inset * 2)));
            const maxOcrWidth = 1280;
            const scale = Math.min(1, maxOcrWidth / sourceWidth);

            canvas.width = Math.max(1, Math.floor(sourceWidth * scale));
            canvas.height = Math.max(1, Math.floor(sourceHeight * scale));

            const context = canvas.getContext('2d', { willReadFrequently: true });
            context.filter = 'grayscale(1) contrast(1.45)';
            context.drawImage(
                video,
                sourceX,
                sourceY,
                sourceWidth,
                sourceHeight,
                0,
                0,
                canvas.width,
                canvas.height
            );
            context.filter = 'none';

            return canvas;
        }

        async function getWorker() {
            if (worker) return worker;

            setStatus('Menyiapkan mesin OCR Bahasa Indonesia. Pemindaian pertama mungkin sedikit lebih lama.');

            worker = await Tesseract.createWorker('ind+eng', 1, {
                logger: (progress) => {
                    if (progress.status === 'recognizing text') {
                        const percent = Math.round(progress.progress * 100);
                        setStatus(`Membaca teks… ${percent}%`);
                    }
                }
            });

            // Halaman buku umumnya satu blok teks; mode ini mempercepat segmentasi OCR.
            await worker.setParameters({ tessedit_pageseg_mode: '6' });

            return worker;
        }

        async function scanText() {
            if (!stream || video.readyState < 2) {
                setStatus('Aktifkan kamera terlebih dahulu.', 'error');
                return;
            }

            if (scanning) return;

            scanning = true;
            const startedAt = performance.now();
            refreshButtons();

            try {
                const ocrWorker = await getWorker();
                const frame = captureFrame();
                setStatus('Membaca teks dari halaman buku…');

                const result = await ocrWorker.recognize(frame);
                const cleanText = result.data.text
                    .replace(/[ \t]+\n/g, '\n')
                    .replace(/\n{3,}/g, '\n\n')
                    .trim();

                if (!cleanText) {
                    setStatus('Teks belum terbaca. Dekatkan kamera, perbaiki pencahayaan, lalu coba lagi.', 'error');
                    return;
                }

                textArea.value = cleanText;
                const elapsedSeconds = ((performance.now() - startedAt) / 1000).toFixed(1);
                setStatus(`Teks berhasil dibaca dalam ${elapsedSeconds} detik. Ditemukan ${cleanText.length} karakter.`);
            } catch (error) {
                console.error(error);
                setStatus('Pemindaian gagal. Pastikan koneksi internet tersedia saat pemindaian pertama.', 'error');
            } finally {
                scanning = false;
                refreshButtons();
            }
        }

        function toggleAutomaticScan() {
            if (!stream) return;

            if (autoScanEnabled) {
                stopAutomaticScan();
                setStatus('Pemindaian otomatis dihentikan.');
                return;
            }

            autoScanEnabled = true;
            autoButton.textContent = 'Pindai otomatis: aktif';
            setStatus('Pemindaian otomatis aktif; pemindaian berikutnya dimulai 6,5 detik setelah OCR selesai.');
            scanText().finally(scheduleAutomaticScan);
        }

        function scheduleAutomaticScan() {
            if (!autoScanEnabled || !stream) return;

            // Hindari scan berikutnya menumpuk saat OCR sebelumnya masih memakai CPU.
            autoTimer = window.setTimeout(async () => {
                autoTimer = null;
                await scanText();
                scheduleAutomaticScan();
            }, 6500);
        }

        function speakText() {
            const text = textArea.value.trim();

            if (!text) {
                setStatus('Belum ada teks untuk dibacakan.', 'error');
                return;
            }

            if (!('speechSynthesis' in window)) {
                setStatus('Browser ini belum mendukung Text-to-Speech.', 'error');
                return;
            }

            window.speechSynthesis.cancel();

            const speech = new SpeechSynthesisUtterance(text);
            const indonesianVoice = window.speechSynthesis
                .getVoices()
                .find((voice) => voice.lang.toLowerCase().startsWith('id'));

            if (indonesianVoice) {
                speech.voice = indonesianVoice;
                speech.lang = indonesianVoice.lang;
            } else {
                speech.lang = 'id-ID';
            }

            speech.rate = Number(rate.value);
            speech.onstart = () => setStatus('Bacara sedang membacakan teks.');
            speech.onend = () => setStatus('Pembacaan selesai.');
            speech.onerror = () => setStatus('Suara tidak dapat diputar. Coba lagi.', 'error');

            window.speechSynthesis.speak(speech);
        }

        startButton.addEventListener('click', startCamera);
        scanButton.addEventListener('click', scanText);
        autoButton.addEventListener('click', toggleAutomaticScan);
        stopButton.addEventListener('click', stopCamera);
        speakButton.addEventListener('click', speakText);
        stopSpeechButton.addEventListener('click', () => {
            window.speechSynthesis.cancel();
            setStatus('Pembacaan dihentikan.');
        });

        rate.addEventListener('input', () => {
            rateValue.textContent = Number(rate.value).toFixed(1);
        });

        window.addEventListener('pagehide', () => {
            stopCamera();
            window.speechSynthesis.cancel();

            if (worker) {
                worker.terminate();
            }
        });

        refreshButtons();
    </script>
</body>
</html>
