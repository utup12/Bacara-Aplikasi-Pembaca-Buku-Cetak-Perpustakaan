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
            padding-bottom: 104px;
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

        .orientation-notice {
            display: none;
        }

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
            min-height: 56px;
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
            border: 2px solid #9dac9f;
            border-radius: 8px;
            background: #eef2ef;
            color: #24352d;
            font-weight: 600;
        }

        #status[data-mode="error"] {
            color: #7c150f;
            background: #ffebe9;
            border-color: #b42318;
        }

        #status[data-mode="detected"] {
            color: #07372f;
            background: #d9f5e6;
            border-color: #0b4f43;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .speak-dock {
            position: fixed;
            z-index: 20;
            left: 50%;
            bottom: max(16px, env(safe-area-inset-bottom));
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: max-content;
        }

        .speak-dock button {
            min-height: 64px;
            box-shadow: 0 4px 16px rgba(7, 55, 47, .28);
        }

        .speak-dock .primary {
            min-width: min(76vw, 360px);
            font-size: 1.15rem;
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
            .speak-dock { width: calc(100% - 28px); }
            .speak-dock .primary { min-width: 0; flex: 1; }
            .speak-dock button { padding-inline: 12px; }
        }

        @media (orientation: landscape) and (max-height: 600px) {
            header { padding: 12px 20px; }
            main { padding-top: 16px; }
            .intro { margin-bottom: 14px; }
            .camera, video { min-height: 220px; }
        }

        @media (orientation: portrait) {
            .orientation-notice {
                position: fixed;
                inset: 0;
                z-index: 100;
                display: grid;
                place-items: center;
                padding: 24px;
                background: #07372f;
                color: #fff;
                text-align: center;
            }

            .orientation-notice div { max-width: 420px; }
            .orientation-notice strong { display: block; font-size: 1.5rem; }
            .orientation-notice p { color: #d9ebe3; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#pemindai">Langsung ke pemindai</a>

    <div class="orientation-notice" id="orientation-notice" role="alert" aria-live="assertive">
        <div>
            <strong>Putar perangkat ke posisi horizontal.</strong>
            <p>Bacara hanya digunakan dalam mode landscape agar kamera dan tombol pembacaan mudah dijangkau.</p>
        </div>
    </div>

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

        <p id="status" role="status" aria-live="polite" aria-atomic="true" data-mode="info">
            Tekan “Aktifkan kamera” untuk memulai.
        </p>

        <div class="grid">
            <section class="panel" id="pemindai" aria-labelledby="judul-kamera">
                <h2 id="judul-kamera">1. Pindai halaman</h2>
                <p>Pastikan teks terang, lurus, dan memenuhi kotak kuning. Setelah kamera aktif, Bacara memantau teks secara otomatis dan memberi panduan suara saat status berubah.</p>

                <div class="camera">
                    <video id="video" autoplay playsinline muted aria-label="Pratinjau kamera buku"></video>
                    <div class="scan-frame" aria-hidden="true">
                        <span>Arahkan teks di dalam kotak</span>
                    </div>
                </div>

                <div class="actions">
                    <button class="primary" id="start-camera" type="button">Aktifkan kamera</button>
                    <button id="scan" type="button" disabled aria-label="Pindai teks satu kali sekarang">Pindai sekali</button>
                    <button id="auto-scan" type="button" disabled>Pemantauan teks: mati</button>
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

            </section>
        </div>
    </main>

    <footer>Bacara MVP · OCR Bahasa Indonesia dan Text-to-Speech di perangkat</footer>

    <canvas id="canvas" hidden></canvas>

    <div class="speak-dock" role="group" aria-label="Kontrol pembacaan teks">
        <button class="primary" id="baca" type="button" aria-describedby="bantuan-baca">Bacakan teks</button>
        <button id="hentikan-suara" type="button">Hentikan suara</button>
    </div>
    <p class="sr-only" id="bantuan-baca">Tombol ini selalu berada di tengah bawah layar. Tekan untuk membacakan hasil teks yang terdeteksi.</p>

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
        const lightCanvas = document.createElement('canvas');

        let stream = null;
        let cameraStarting = false;
        let worker = null;
        let scanning = false;
        let autoTimer = null;
        let autoScanEnabled = false;
        let detectionState = null;
        let lastProgressUpdate = -25;
        let isReadingText = false;
        let speechSession = 0;
        let lightTimer = null;
        let torchAvailable = false;
        let torchEnabled = false;
        let isMeasuringLight = false;

        const DARKNESS_THRESHOLD = 58;

        function setStatus(message, mode = 'info') {
            statusBox.textContent = message;
            statusBox.dataset.mode = mode;
        }

        function getIndonesianVoice() {
            return window.speechSynthesis
                .getVoices()
                .find((voice) => voice.lang.toLowerCase().startsWith('id'));
        }

        function speakGuidance(message) {
            if (isReadingText || !('speechSynthesis' in window)) return;

            const guidance = new SpeechSynthesisUtterance(message);
            const indonesianVoice = getIndonesianVoice();

            if (indonesianVoice) {
                guidance.voice = indonesianVoice;
                guidance.lang = indonesianVoice.lang;
            } else {
                guidance.lang = 'id-ID';
            }

            guidance.rate = 1;
            window.speechSynthesis.cancel();
            window.speechSynthesis.speak(guidance);
        }

        function reportDetection(hasText) {
            const nextState = hasText ? 'detected' : 'missing';

            // Hindari suara berulang saat kamera masih melihat halaman yang sama.
            if (detectionState === nextState) return;

            detectionState = nextState;
            const message = hasText
                ? 'Teks terdeteksi.'
                : 'Tulisan belum terdeteksi. Arahkan kamera lebih dekat ke halaman buku.';

            setStatus(message, hasText ? 'detected' : 'error');
            speakGuidance(message);
        }

        function refreshButtons() {
            const cameraOn = stream !== null;
            scanButton.disabled = !cameraOn || scanning;
            autoButton.disabled = !cameraOn;
            stopButton.disabled = !cameraOn;
            startButton.disabled = cameraOn || cameraStarting;
        }

        function isPortraitOrientation() {
            return window.matchMedia('(orientation: portrait)').matches;
        }

        async function requestLandscapeMode() {
            if (!window.screen?.orientation || typeof window.screen.orientation.lock !== 'function') return;

            try {
                await window.screen.orientation.lock('landscape');
            } catch (error) {
                // Sebagian browser hanya mengizinkan lock pada mode layar penuh.
                // Layar instruksi CSS di portrait tetap memastikan Bacara hanya dipakai horizontal.
                console.info('Kunci orientasi tidak tersedia pada browser ini.', error);
            }
        }

        let portraitNoticeActive = false;

        function requireLandscapeMode() {
            if (!isPortraitOrientation()) {
                portraitNoticeActive = false;
                return true;
            }

            if (!portraitNoticeActive) {
                const message = 'Putar perangkat ke posisi horizontal untuk menggunakan Bacara.';
                setStatus(message, 'error');
                speakGuidance(message);
                portraitNoticeActive = true;
            }

            return false;
        }

        function handleOrientationChange() {
            if (!isPortraitOrientation()) {
                portraitNoticeActive = false;
                return;
            }

            if (stream) stopCamera();
            requireLandscapeMode();
        }

        async function startCamera() {
            if (stream || cameraStarting) return;

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus('Kamera memerlukan browser modern dan alamat HTTPS atau localhost.', 'error');
                return;
            }

            cameraStarting = true;
            setStatus('Menyiapkan kamera. Izinkan akses jika diminta browser.');
            refreshButtons();

            try {
                await requestLandscapeMode();

                if (!requireLandscapeMode()) return;

                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1280, max: 1280 },
                        height: { ideal: 720, max: 720 },
                        frameRate: { ideal: 24, max: 24 }
                    },
                    audio: false
                });

                // Izin dapat selesai setelah perangkat diputar; jangan aktifkan kamera pada mode portrait.
                if (!requireLandscapeMode()) {
                    stream.getTracks().forEach((track) => track.stop());
                    stream = null;
                    return;
                }

                video.srcObject = stream;
                await video.play();
                detectionState = null;
                setStatus('Kamera aktif. Pemantauan teks dimulai otomatis.');
                refreshButtons();
                startLightMonitor();
                startAutomaticScan();
            } catch (error) {
                setStatus('Kamera tidak dapat diakses. Izinkan akses kamera di browser, lalu coba lagi.', 'error');
                console.error(error);
            } finally {
                cameraStarting = false;
                refreshButtons();
            }
        }

        function stopAutomaticScan() {
            autoScanEnabled = false;

            if (autoTimer !== null) {
                window.clearTimeout(autoTimer);
                autoTimer = null;
            }
            autoButton.textContent = 'Pemantauan teks: mati';
        }

        function stopCamera() {
            stopAutomaticScan();
            stopLightMonitor();
            detectionState = null;

            if (stream) {
                stream.getTracks().forEach((track) => track.stop());
                stream = null;
            }

            video.srcObject = null;
            setStatus('Kamera dimatikan.');
            refreshButtons();
        }

        function getVideoTrack() {
            return stream?.getVideoTracks()[0] ?? null;
        }

        function estimateLightLevel() {
            if (!video.videoWidth || !video.videoHeight) return null;

            const sampleWidth = 64;
            const sampleHeight = 36;
            lightCanvas.width = sampleWidth;
            lightCanvas.height = sampleHeight;

            const lightContext = lightCanvas.getContext('2d', { willReadFrequently: true });
            lightContext.drawImage(video, 0, 0, sampleWidth, sampleHeight);

            const pixels = lightContext.getImageData(0, 0, sampleWidth, sampleHeight).data;
            let total = 0;
            let samples = 0;

            // Ambil setiap empat piksel saja agar pemantauan cahaya tetap sangat ringan.
            for (let index = 0; index < pixels.length; index += 16) {
                total += (pixels[index] * 0.2126) + (pixels[index + 1] * 0.7152) + (pixels[index + 2] * 0.0722);
                samples += 1;
            }

            return samples ? total / samples : null;
        }

        async function enableTorchForDarkness() {
            const track = getVideoTrack();
            if (!track || !torchAvailable || torchEnabled) return;

            try {
                await track.applyConstraints({ advanced: [{ torch: true }] });
                torchEnabled = true;
                const message = 'Cahaya kurang. Senter kamera dinyalakan otomatis.';
                setStatus(message);
                speakGuidance(message);
            } catch (error) {
                // Sebagian perangkat melaporkan kemampuan torch, tetapi membatasinya pada mode tertentu.
                torchAvailable = false;
                console.info('Senter kamera tidak dapat dinyalakan otomatis.', error);
            }
        }

        async function checkLightLevel() {
            if (!stream || !torchAvailable || torchEnabled || isMeasuringLight) return;

            isMeasuringLight = true;

            try {
                const lightLevel = estimateLightLevel();
                if (lightLevel !== null && lightLevel < DARKNESS_THRESHOLD) {
                    await enableTorchForDarkness();
                }
            } catch (error) {
                console.info('Pengukuran cahaya kamera tidak tersedia.', error);
            } finally {
                isMeasuringLight = false;
            }
        }

        function startLightMonitor() {
            const track = getVideoTrack();
            const capabilities = typeof track?.getCapabilities === 'function'
                ? track.getCapabilities()
                : {};

            torchAvailable = capabilities.torch === true;
            torchEnabled = false;

            if (!torchAvailable) return;

            window.setTimeout(checkLightLevel, 700);
            lightTimer = window.setInterval(checkLightLevel, 1500);
        }

        function stopLightMonitor() {
            if (lightTimer !== null) {
                window.clearInterval(lightTimer);
                lightTimer = null;
            }

            torchAvailable = false;
            torchEnabled = false;
            isMeasuringLight = false;
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

            const lightLevel = estimateLightLevel();
            const context = canvas.getContext('2d', { willReadFrequently: true });
            context.filter = lightLevel !== null && lightLevel < 90
                ? 'grayscale(1) contrast(1.85) brightness(1.22)'
                : 'grayscale(1) contrast(1.45)';
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
                        if (percent === 100 || percent - lastProgressUpdate >= 25) {
                            lastProgressUpdate = percent;
                            setStatus(`Membaca teks… ${percent}%`);
                        }
                    }
                }
            });

            // Mode blok teks tetap cepat, sambil mempertahankan spasi, angka, tanda baca, dan simbol daftar.
            await worker.setParameters({
                tessedit_pageseg_mode: '6',
                preserve_interword_spaces: '1'
            });

            return worker;
        }

        function normalizeOcrText(rawText) {
            return rawText
                .replace(/[ \t]+\n/g, '\n')
                .replace(/\n{3,}/g, '\n\n')
                // Samakan variasi ikon daftar agar mudah dibaca oleh pembaca layar.
                .replace(/^([ \t]*)([\u2022\u25CF\u25CB\u25E6\u25AA\u25AB\u25A0\u25A1\u25C6\u25C7])\s*/gm, '$1\u2022 ')
                .replace(/^([ \t]*)(\d+)\s*[\)\]]\s*/gm, '$1$2. ')
                .replace(/^([ \t]*)([A-Za-z])\s*[\)\]]\s*/gm, '$1$2. ')
                .trim();
        }

        function makeTextSpeakable(text) {
            return text
                .replace(/^([ \t]*)\u2022\s*/gm, '$1Poin. ')
                .replace(/^([ \t]*)(\d+)[.)]\s*/gm, '$1Nomor $2. ')
                .replace(/\u2713|\u2714/g, ' tanda centang ')
                .replace(/\u2717|\u2715|\u2716/g, ' tanda silang ')
                .replace(/\u2192/g, ' panah kanan ')
                .replace(/\u2190/g, ' panah kiri ')
                .replace(/\u2191/g, ' panah atas ')
                .replace(/\u2193/g, ' panah bawah ');
        }

        async function scanText() {
            if (!stream || video.readyState < 2) {
                setStatus('Aktifkan kamera terlebih dahulu.', 'error');
                return;
            }

            if (scanning) return;

            scanning = true;
            refreshButtons();

            try {
                const ocrWorker = await getWorker();
                const frame = captureFrame();
                lastProgressUpdate = -25;
                setStatus('Membaca teks dari halaman buku…');

                const result = await ocrWorker.recognize(frame);
                const cleanText = normalizeOcrText(result.data.text);

                if (!cleanText) {
                    reportDetection(false);
                    return;
                }

                textArea.value = cleanText;
                reportDetection(true);
            } catch (error) {
                console.error(error);
                setStatus('Pemindaian gagal. Pastikan koneksi internet tersedia saat pemindaian pertama.', 'error');
            } finally {
                scanning = false;
                refreshButtons();
            }
        }

        function startAutomaticScan() {
            if (!stream || autoScanEnabled) return;

            autoScanEnabled = true;
            autoButton.textContent = 'Pemantauan teks: aktif';
            autoTimer = window.setTimeout(async () => {
                autoTimer = null;
                await scanText();
                scheduleAutomaticScan();
            }, 500);
        }

        function toggleAutomaticScan() {
            if (!stream) return;

            if (autoScanEnabled) {
                stopAutomaticScan();
                setStatus('Pemantauan teks dihentikan.');
                return;
            }

            detectionState = null;
            startAutomaticScan();
            setStatus('Pemantauan teks diaktifkan.');
        }

        function scheduleAutomaticScan() {
            if (!autoScanEnabled || !stream) return;

            // Hindari scan berikutnya menumpuk saat OCR sebelumnya masih memakai CPU.
            autoTimer = window.setTimeout(async () => {
                autoTimer = null;
                await scanText();
                scheduleAutomaticScan();
            }, 5000);
        }

        function speakText() {
            const text = textArea.value.trim();

            if (!text) {
                const message = 'Belum ada teks untuk dibacakan.';
                setStatus(message, 'error');
                speakGuidance(message);
                return;
            }

            if (!('speechSynthesis' in window)) {
                setStatus('Browser ini belum mendukung Text-to-Speech.', 'error');
                return;
            }

            stopAutomaticScan();
            isReadingText = true;
            const currentSpeechSession = ++speechSession;
            window.speechSynthesis.cancel();

            const speech = new SpeechSynthesisUtterance(makeTextSpeakable(text));
            const indonesianVoice = getIndonesianVoice();

            if (indonesianVoice) {
                speech.voice = indonesianVoice;
                speech.lang = indonesianVoice.lang;
            } else {
                speech.lang = 'id-ID';
            }

            speech.rate = Number(rate.value);
            speech.onstart = () => setStatus('Bacara sedang membacakan teks.');
            speech.onend = () => {
                if (currentSpeechSession !== speechSession) return;

                isReadingText = false;
                setStatus('Pembacaan selesai. Pemantauan teks dilanjutkan.');
                startAutomaticScan();
            };
            speech.onerror = () => {
                if (currentSpeechSession !== speechSession) return;

                isReadingText = false;
                setStatus('Suara tidak dapat diputar. Coba lagi.', 'error');
                startAutomaticScan();
            };

            window.speechSynthesis.speak(speech);
        }

        startButton.addEventListener('click', startCamera);
        scanButton.addEventListener('click', scanText);
        autoButton.addEventListener('click', toggleAutomaticScan);
        stopButton.addEventListener('click', stopCamera);
        speakButton.addEventListener('click', speakText);
        stopSpeechButton.addEventListener('click', () => {
            speechSession += 1;
            isReadingText = false;
            window.speechSynthesis.cancel();
            setStatus('Pembacaan dihentikan. Pemantauan teks dilanjutkan.');
            startAutomaticScan();
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

        window.addEventListener('orientationchange', handleOrientationChange);
        window.addEventListener('resize', handleOrientationChange);

        refreshButtons();
        window.addEventListener('load', () => {
            if (requireLandscapeMode()) startCamera();
        }, { once: true });
    </script>
</body>
</html>
