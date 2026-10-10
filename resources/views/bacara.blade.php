<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-store, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
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

        main {
            width: min(100%, 1100px);
            margin: 0 auto;
        }

        main { padding: 12px 20px 48px; }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .panel {
            background: var(--putih);
            border: 1px solid var(--garis);
            border-radius: 16px;
            padding: 12px;
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
            min-height: 320px;
            background: #17201d;
            border-radius: 12px;
            margin: 0;
        }

        video {
            display: block;
            width: 100%;
            min-height: 320px;
            object-fit: cover;
        }

        .scan-guide {
            position: absolute;
            inset: 7% 5%;
            border: 3px solid rgba(255, 255, 255, .94);
            border-radius: 8px;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, .26);
            pointer-events: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .scan-guide::before {
            content: 'Letakkan satu halaman buku di dalam bingkai';
            position: absolute;
            top: 10px;
            left: 50%;
            width: max-content;
            max-width: calc(100% - 20px);
            transform: translateX(-50%);
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, .68);
            color: #fff;
            font-size: .8rem;
            font-weight: 700;
            text-align: center;
        }

        .camera[data-scan-state="steady"] .scan-guide {
            border-color: var(--kuning);
        }

        .camera[data-scan-state="ready"] .scan-guide {
            border-color: #55d68a;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, .15);
        }

        .camera[data-scan-state="reading"] .scan-guide {
            border-color: #68b6ff;
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

        .hold-text-button {
            position: fixed;
            z-index: 30;
            top: max(12px, env(safe-area-inset-top));
            right: 12px;
            min-height: 48px;
            padding-inline: 16px;
            background: var(--putih);
            box-shadow: 0 3px 12px rgba(12, 48, 38, .25);
        }

        .hold-text-button[aria-pressed="true"] {
            background: var(--kuning);
            border-color: #6d5200;
            color: #3d2e00;
        }

        .torch-button {
            position: fixed;
            z-index: 30;
            top: max(12px, env(safe-area-inset-top));
            left: 12px;
            min-height: 48px;
            padding-inline: 16px;
            background: var(--putih);
            box-shadow: 0 3px 12px rgba(12, 48, 38, .25);
        }

        .torch-button[aria-pressed="true"] {
            background: var(--kuning);
            border-color: #6d5200;
            color: #3d2e00;
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
            width: min(calc(100% - 28px), 640px);
        }

        .speak-dock button {
            min-width: 0;
            min-height: 64px;
            flex: 1 1 0;
            box-shadow: 0 4px 16px rgba(7, 55, 47, .28);
        }

        .speak-dock .primary {
            flex: 1;
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

        @media (max-width: 760px) {
            body {
                padding-bottom: 124px;
                font-size: 17px;
            }
            .panel { padding: 16px; }
            main { padding: 10px 14px 40px; }
            .speak-dock {
                gap: 6px;
                width: calc(100% - 20px);
            }
            .speak-dock .primary { font-size: .88rem; }
            .speak-dock button {
                min-height: 60px;
                padding: 5px 3px;
                font-size: .78rem;
                line-height: 1.25;
            }
        }

        @media (orientation: landscape) and (max-height: 600px) {
            .camera, video { min-height: 220px; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#hasil-teks">Langsung ke hasil teks</a>
    <button class="torch-button" id="torch-toggle" type="button" aria-pressed="false" hidden>Nyalakan senter</button>
    <button class="hold-text-button" id="hold-text" type="button" aria-pressed="false">Tahan teks</button>

    <main>
        <h1 class="sr-only">Bacara, pembaca buku melalui kamera</h1>
        <p id="status" role="status" aria-live="polite" aria-atomic="true" data-mode="info">Panduan suara sedang disiapkan.</p>

        <div class="grid">
            <section class="panel" id="pemindai" aria-labelledby="judul-kamera">
                <h2 id="judul-kamera" class="sr-only">Kamera</h2>

                <div class="camera">
                    <video id="video" autoplay playsinline muted aria-label="Pratinjau kamera buku"></video>
                    <div class="scan-guide" aria-hidden="true"></div>
                </div>
                <p class="small">Masukkan satu halaman buku ke bingkai, tahan perangkat sampai stabil, lalu pindai.</p>
            </section>

            <section class="panel" aria-labelledby="judul-teks">
                <h2 id="judul-teks" class="sr-only">Hasil teks</h2>

                <label for="hasil-teks">Hasil teks</label>
                <textarea id="hasil-teks" placeholder="Hasil teks akan muncul di sini."></textarea>

                <label for="kecepatan">Kecepatan suara: <output id="nilai-kecepatan">0.9</output></label>
                <input id="kecepatan" type="range" min="0.6" max="1.3" step="0.1" value="0.9">

            </section>
        </div>
    </main>

    <canvas id="canvas" hidden></canvas>
    <input id="photo-capture" type="file" accept="image/*" capture="environment" hidden>

    <div class="speak-dock" role="group" aria-label="Kontrol kamera dan pembacaan teks">
        <button class="primary" id="baca" type="button" aria-describedby="bantuan-baca">Bacakan teks</button>
        <button id="camera-toggle" type="button" aria-pressed="false">Aktifkan kamera</button>
        <button id="scan-now" type="button">Pindai halaman</button>
        <button id="hentikan-suara" type="button">Hentikan suara</button>
        <button id="refresh-text" type="button" aria-label="Refresh hasil teks">Refresh</button>
    </div>
    <p class="sr-only" id="bantuan-baca">Ketuk sekali untuk mendengar fungsi tombol. Ketuk dua kali dengan cepat untuk menjalankannya.</p>

    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@6/dist/tesseract.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoCaptureInput = document.getElementById('photo-capture');
        const cameraPreview = document.querySelector('.camera');
        const statusBox = document.getElementById('status');
        const textArea = document.getElementById('hasil-teks');
        const speakButton = document.getElementById('baca');
        const cameraToggleButton = document.getElementById('camera-toggle');
        const stopSpeechButton = document.getElementById('hentikan-suara');
        const refreshTextButton = document.getElementById('refresh-text');
        const scanNowButton = document.getElementById('scan-now');
        const holdTextButton = document.getElementById('hold-text');
        const torchToggleButton = document.getElementById('torch-toggle');
        const rate = document.getElementById('kecepatan');
        const rateValue = document.getElementById('nilai-kecepatan');
        const lightCanvas = document.createElement('canvas');
        const frameQualityCanvas = document.createElement('canvas');
        const binaryCanvas = document.createElement('canvas');

        let stream = null;
        let cameraStarting = false;
        let worker = null;
        let scanning = false;
        let realtimeScanTimer = null;
        let realtimeRecognitionActive = false;
        let lastDetectedText = '';
        let lastDetectedScore = 0;
        let lastDetectedConfidence = 0;
        let lastRecognitionState = '';
        let textEditedManually = false;
        let textHeld = false;
        let isReadingText = false;
        let speechSession = 0;
        let initialGuidancePlayed = false;
        let lightTimer = null;
        let torchAvailable = false;
        let torchEnabled = false;
        let isMeasuringLight = false;
        let scanRequestPending = false;
        let previousFramePixels = null;
        let steadyFrameSamples = 0;
        let lastAutoScanAt = 0;

        const DARKNESS_THRESHOLD = 58;
        const REALTIME_SCAN_DELAY = 500;
        // Tesseract sering memberi confidence rendah untuk cetakan buku yang
        // kecil atau sedikit miring. Jangan membuang teks yang tetap berguna
        // hanya karena nilainya belum mendekati hasil dokumen digital.
        const MINIMUM_OCR_CONFIDENCE = 26;
        const MINIMUM_WORD_CONFIDENCE = 22;
        const MINIMUM_TEXT_LENGTH = 6;
        const MINIMUM_LETTER_COUNT = 4;
        const OCR_START_TIMEOUT = 45000;
        const OCR_RECOGNITION_TIMEOUT = 45000;
        const VIDEO_OCR_TARGET_WIDTH = 2048;
        // Ukuran ini cukup tajam untuk cetakan buku, tetapi jauh lebih ringan
        // daripada foto asli pada RAM dan CPU ponsel.
        const PHOTO_OCR_TARGET_WIDTH = 1920;
        const REQUIRED_STEADY_FRAME_SAMPLES = 3;
        const FRAME_CHANGE_THRESHOLD = 10;
        const MINIMUM_SHARPNESS = 18;
        const AUTO_SCAN_COOLDOWN = 2400;
        const DOUBLE_ACTIVATION_DELAY = 450;

        function setStatus(message, mode = 'info') {
            statusBox.textContent = message;
            statusBox.dataset.mode = mode;
        }

        function setCameraScanState(state) {
            cameraPreview.dataset.scanState = state;
        }

        function announceScanProgress(message, mode = 'info') {
            setStatus(message, mode);
            speakGuidance(message);
        }

        function setScanRequestPending(isPending) {
            scanRequestPending = isPending;
            refreshCameraToggleButton();
        }

        function resetFrameStability() {
            previousFramePixels = null;
            steadyFrameSamples = 0;
        }

        function getIndonesianVoice() {
            const voices = window.speechSynthesis.getVoices();

            return voices.find((voice) => voice.lang.toLowerCase() === 'id-id')
                ?? voices.find((voice) => voice.lang.toLowerCase().startsWith('id'));
        }

        function configureIndonesianVoice(utterance) {
            const indonesianVoice = getIndonesianVoice();
            utterance.lang = indonesianVoice?.lang ?? 'id-ID';

            if (indonesianVoice) {
                utterance.voice = indonesianVoice;
            }

            return Boolean(indonesianVoice);
        }

        function waitForIndonesianVoice(timeout = 800) {
            if (!('speechSynthesis' in window) || getIndonesianVoice()) {
                return Promise.resolve();
            }

            return new Promise((resolve) => {
                let finished = false;
                const finish = () => {
                    if (finished) return;

                    finished = true;
                    window.speechSynthesis.removeEventListener('voiceschanged', voicesChanged);
                    resolve();
                };
                const voicesChanged = () => {
                    if (getIndonesianVoice()) finish();
                };

                window.speechSynthesis.addEventListener('voiceschanged', voicesChanged);
                window.setTimeout(finish, timeout);
            });
        }

        function speakMessage(message) {
            if (!('speechSynthesis' in window)) return;

            speechSession += 1;
            isReadingText = false;
            window.speechSynthesis.cancel();

            const guidance = new SpeechSynthesisUtterance(message);
            configureIndonesianVoice(guidance);

            guidance.rate = 1;
            window.speechSynthesis.cancel();
            window.speechSynthesis.speak(guidance);
        }

        function speakGuidance(message) {
            if (isReadingText) return;

            speakMessage(message);
        }

        function refreshCameraToggleButton() {
            const cameraOn = stream !== null;
            cameraToggleButton.disabled = cameraStarting;
            cameraToggleButton.textContent = cameraOn ? 'Matikan kamera' : 'Aktifkan kamera';
            cameraToggleButton.setAttribute('aria-pressed', String(cameraOn));
            cameraToggleButton.classList.toggle('warning', cameraOn);
            scanNowButton.disabled = !cameraOn || cameraStarting || scanning || scanRequestPending || textHeld;
        }

        function refreshTorchButton() {
            const canUseTorch = stream !== null && torchAvailable;
            torchToggleButton.hidden = !canUseTorch;
            torchToggleButton.disabled = !canUseTorch;
            torchToggleButton.textContent = torchEnabled ? 'Matikan senter' : 'Nyalakan senter';
            torchToggleButton.setAttribute('aria-pressed', String(torchEnabled));
        }

        async function startCamera() {
            if (stream || cameraStarting) return;

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus('Kamera memerlukan browser modern dan alamat HTTPS atau localhost.', 'error');
                return;
            }

            cameraStarting = true;
            setStatus('Menyiapkan kamera. Izinkan akses jika diminta browser.');
            refreshCameraToggleButton();

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 2560 },
                        height: { ideal: 1440 },
                        frameRate: { ideal: 30, max: 30 }
                    },
                    audio: false
                });

                video.srcObject = stream;
                await video.play();
                await waitForCameraFrame();
                await optimizeCameraForText();
                setCameraScanState('waiting');
                setStatus('Kamera aktif. Masukkan satu halaman ke bingkai dan tahan perangkat tetap stabil.');
                refreshCameraToggleButton();
                startLightMonitor();
                startRealtimeTextRecognition();
            } catch (error) {
                setStatus('Kamera tidak dapat diakses. Izinkan akses kamera di browser, lalu coba lagi.', 'error');
                console.error(error);
            } finally {
                cameraStarting = false;
                refreshCameraToggleButton();
            }
        }

        function stopCamera() {
            stopRealtimeTextRecognition();
            stopLightMonitor();
            scanRequestPending = false;

            if (stream) {
                stream.getTracks().forEach((track) => track.stop());
                stream = null;
            }

            video.srcObject = null;
            resetFrameStability();
            setCameraScanState('waiting');
            setStatus('Kamera dimatikan.');
            refreshCameraToggleButton();
        }

        function toggleCamera() {
            if (stream) {
                stopCamera();
                return;
            }

            startCamera();
        }

        function getVideoTrack() {
            return stream?.getVideoTracks()[0] ?? null;
        }

        function waitForCameraFrame() {
            if (video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA) {
                return Promise.resolve();
            }

            return new Promise((resolve) => {
                const ready = () => resolve();
                video.addEventListener('loadeddata', ready, { once: true });
                window.setTimeout(ready, 1500);
            });
        }

        async function optimizeCameraForText() {
            const track = getVideoTrack();
            const capabilities = typeof track?.getCapabilities === 'function'
                ? track.getCapabilities()
                : {};
            const advanced = {};

            if (capabilities.focusMode?.includes('continuous')) {
                advanced.focusMode = 'continuous';
            }

            if (capabilities.exposureMode?.includes('continuous')) {
                advanced.exposureMode = 'continuous';
            }

            if (capabilities.whiteBalanceMode?.includes('continuous')) {
                advanced.whiteBalanceMode = 'continuous';
            }

            if (!Object.keys(advanced).length) return;

            try {
                await track.applyConstraints({ advanced: [advanced] });
            } catch (error) {
                // Pengaturan fokus lanjutan tidak tersedia pada semua kamera Android.
                console.info('Optimasi fokus kamera tidak tersedia.', error);
            }
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
                refreshTorchButton();
                const message = 'Cahaya kurang. Senter kamera dinyalakan otomatis.';
                setStatus(message);
                speakGuidance(message);
            } catch (error) {
                // Sebagian perangkat melaporkan kemampuan torch, tetapi membatasinya pada mode tertentu.
                torchAvailable = false;
                refreshTorchButton();
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
            refreshTorchButton();

            if (!torchAvailable) return;

            window.setTimeout(checkLightLevel, 700);
            lightTimer = window.setInterval(checkLightLevel, 1500);
        }

        async function toggleTorch() {
            const track = getVideoTrack();
            if (!track || !torchAvailable) {
                const message = 'Senter tidak tersedia pada kamera ini.';
                setStatus(message, 'error');
                speakGuidance(message);
                return;
            }

            try {
                const shouldEnable = !torchEnabled;
                await track.applyConstraints({ advanced: [{ torch: shouldEnable }] });
                torchEnabled = shouldEnable;
                refreshTorchButton();
                const message = shouldEnable ? 'Senter kamera dinyalakan.' : 'Senter kamera dimatikan.';
                setStatus(message);
                speakGuidance(message);
            } catch (error) {
                console.info('Senter kamera tidak dapat diubah.', error);
                const message = 'Senter kamera tidak dapat diubah pada perangkat ini.';
                setStatus(message, 'error');
                speakGuidance(message);
            }
        }

        function stopLightMonitor() {
            if (lightTimer !== null) {
                window.clearInterval(lightTimer);
                lightTimer = null;
            }

            torchAvailable = false;
            torchEnabled = false;
            isMeasuringLight = false;
            refreshTorchButton();
        }

        function captureFrame() {
            if (!video.videoWidth || !video.videoHeight) {
                throw new Error('Ukuran video belum tersedia.');
            }

            return renderOcrImage(
                video,
                video.videoWidth,
                video.videoHeight,
                VIDEO_OCR_TARGET_WIDTH,
                estimateLightLevel()
            );
        }

        function renderOcrImage(source, sourceImageWidth, sourceImageHeight, maxOcrWidth, lightLevel = null) {
            // Samakan area OCR dengan bingkai yang dilihat pengguna, bukan seluruh video.
            const insetX = 0.05;
            const insetY = 0.07;
            const sourceX = Math.floor(sourceImageWidth * insetX);
            const sourceY = Math.floor(sourceImageHeight * insetY);
            const sourceWidth = Math.floor(sourceImageWidth * (1 - (insetX * 2)));
            const sourceHeight = Math.floor(sourceImageHeight * (1 - (insetY * 2)));
            // Sedikit pembesaran membantu bila kamera hanya dapat menyediakan video HD.
            const scale = Math.min(1.5, maxOcrWidth / sourceWidth);

            canvas.width = Math.max(1, Math.floor(sourceWidth * scale));
            canvas.height = Math.max(1, Math.floor(sourceHeight * scale));

            const context = canvas.getContext('2d', { willReadFrequently: true });
            context.imageSmoothingEnabled = true;
            context.imageSmoothingQuality = 'high';
            context.filter = lightLevel !== null && lightLevel < 90
                ? 'grayscale(1) contrast(1.75) brightness(1.28)'
                : 'grayscale(1) contrast(1.42) brightness(1.08)';
            context.drawImage(
                source,
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
            enhanceDocumentImage(context, canvas.width, canvas.height);

            return canvas;
        }

        async function renderPhotoFile(file) {
            if (!file || !('createImageBitmap' in window)) {
                throw new Error('Browser tidak dapat memproses foto resolusi penuh.');
            }

            const bitmap = await createImageBitmap(file);
            try {
                return renderOcrImage(bitmap, bitmap.width, bitmap.height, PHOTO_OCR_TARGET_WIDTH);
            } finally {
                bitmap.close?.();
            }
        }

        async function takeHighResolutionPhoto() {
            const track = getVideoTrack();
            if (!track || !('ImageCapture' in window) || !('createImageBitmap' in window)) {
                return null;
            }

            try {
                const photo = await new ImageCapture(track).takePhoto();
                return await renderPhotoFile(photo);
            } catch (error) {
                // Sebagian browser Android mendukung video kamera, tetapi tidak mendukung foto resolusi penuh.
                console.info('Foto resolusi penuh tidak tersedia; memakai frame video.', error);
                return null;
            }
        }

        async function captureBestOcrFrame({ preferPhoto = false, photoFile = null } = {}) {
            if (photoFile) return renderPhotoFile(photoFile);

            if (preferPhoto) {
                const photoFrame = await takeHighResolutionPhoto();
                if (photoFrame) return photoFrame;
            }

            return captureFrame();
        }

        function clampByte(value) {
            return Math.max(0, Math.min(255, Math.round(value)));
        }

        function percentileFromHistogram(histogram, total, percentile) {
            const target = total * percentile;
            let accumulated = 0;

            for (let value = 0; value < histogram.length; value += 1) {
                accumulated += histogram[value];
                if (accumulated >= target) return value;
            }

            return histogram.length - 1;
        }

        function enhanceDocumentImage(context, width, height) {
            const imageData = context.getImageData(0, 0, width, height);
            const { data } = imageData;
            const histogram = new Uint32Array(256);
            const pixelCount = width * height;

            for (let index = 0; index < data.length; index += 4) {
                const luminance = clampByte(
                    (data[index] * 0.2126)
                    + (data[index + 1] * 0.7152)
                    + (data[index + 2] * 0.0722)
                );
                histogram[luminance] += 1;
            }

            const blackPoint = percentileFromHistogram(histogram, pixelCount, 0.015);
            const whitePoint = percentileFromHistogram(histogram, pixelCount, 0.985);
            const range = Math.max(24, whitePoint - blackPoint);

            for (let index = 0; index < data.length; index += 4) {
                const luminance = (data[index] * 0.2126)
                    + (data[index + 1] * 0.7152)
                    + (data[index + 2] * 0.0722);
                const normalized = clampByte(((luminance - blackPoint) * 255) / range);
                const contrasted = clampByte(((normalized - 128) * 1.22) + 128);
                // Putihkan latar kertas tanpa memaksakan threshold hitam/putih yang
                // dapat menghilangkan huruf kecil atau tanda baca.
                const enhanced = contrasted > 210
                    ? clampByte(210 + ((contrasted - 210) * 1.5))
                    : contrasted;

                data[index] = enhanced;
                data[index + 1] = enhanced;
                data[index + 2] = enhanced;
            }

            context.putImageData(imageData, 0, 0);
        }

        function createAdaptiveDocumentCanvas(source) {
            const width = source.width;
            const height = source.height;
            const pixelCount = width * height;
            const stride = width + 1;
            const localWindowRadius = Math.max(12, Math.min(48, Math.round(Math.min(width, height) * 0.012)));

            binaryCanvas.width = width;
            binaryCanvas.height = height;

            const context = binaryCanvas.getContext('2d', { willReadFrequently: true });
            context.drawImage(source, 0, 0);
            const imageData = context.getImageData(0, 0, width, height);
            const { data } = imageData;
            const grayscale = new Uint8Array(pixelCount);
            const integral = new Uint32Array((width + 1) * (height + 1));

            for (let y = 0; y < height; y += 1) {
                let rowSum = 0;
                const integralRow = (y + 1) * stride;
                const previousIntegralRow = y * stride;

                for (let x = 0; x < width; x += 1) {
                    const pixel = (y * width) + x;
                    const dataIndex = pixel * 4;
                    const value = clampByte(
                        (data[dataIndex] * 0.2126)
                        + (data[dataIndex + 1] * 0.7152)
                        + (data[dataIndex + 2] * 0.0722)
                    );

                    grayscale[pixel] = value;
                    rowSum += value;
                    integral[integralRow + x + 1] = integral[previousIntegralRow + x + 1] + rowSum;
                }
            }

            for (let y = 0; y < height; y += 1) {
                const top = Math.max(0, y - localWindowRadius);
                const bottom = Math.min(height - 1, y + localWindowRadius);

                for (let x = 0; x < width; x += 1) {
                    const left = Math.max(0, x - localWindowRadius);
                    const right = Math.min(width - 1, x + localWindowRadius);
                    const total = integral[((bottom + 1) * stride) + right + 1]
                        - integral[(top * stride) + right + 1]
                        - integral[((bottom + 1) * stride) + left]
                        + integral[(top * stride) + left];
                    const localMean = total / ((right - left + 1) * (bottom - top + 1));
                    const threshold = Math.max(40, localMean * 0.87);
                    const value = grayscale[(y * width) + x] < threshold ? 0 : 255;
                    const dataIndex = ((y * width) + x) * 4;

                    data[dataIndex] = value;
                    data[dataIndex + 1] = value;
                    data[dataIndex + 2] = value;
                }
            }

            context.putImageData(imageData, 0, 0);
            return binaryCanvas;
        }

        function inspectFrameQuality() {
            if (!video.videoWidth || !video.videoHeight) return null;

            const sampleWidth = 96;
            const sampleHeight = 54;
            frameQualityCanvas.width = sampleWidth;
            frameQualityCanvas.height = sampleHeight;

            const context = frameQualityCanvas.getContext('2d', { willReadFrequently: true });
            const insetX = video.videoWidth * 0.05;
            const insetY = video.videoHeight * 0.07;
            context.drawImage(
                video,
                insetX,
                insetY,
                video.videoWidth - (insetX * 2),
                video.videoHeight - (insetY * 2),
                0,
                0,
                sampleWidth,
                sampleHeight
            );

            const rgba = context.getImageData(0, 0, sampleWidth, sampleHeight).data;
            const pixels = new Uint8Array(sampleWidth * sampleHeight);
            let brightness = 0;

            for (let pixel = 0, index = 0; index < rgba.length; pixel += 1, index += 4) {
                const luminance = clampByte(
                    (rgba[index] * 0.2126)
                    + (rgba[index + 1] * 0.7152)
                    + (rgba[index + 2] * 0.0722)
                );
                pixels[pixel] = luminance;
                brightness += luminance;
            }

            let movement = 0;
            if (previousFramePixels) {
                for (let index = 0; index < pixels.length; index += 1) {
                    movement += Math.abs(pixels[index] - previousFramePixels[index]);
                }
                movement /= pixels.length;
            } else {
                movement = Number.POSITIVE_INFINITY;
            }

            let edgeStrength = 0;
            let edgeSamples = 0;
            for (let y = 1; y < sampleHeight - 1; y += 2) {
                for (let x = 1; x < sampleWidth - 1; x += 2) {
                    const index = (y * sampleWidth) + x;
                    edgeStrength += Math.abs(pixels[index + 1] - pixels[index - 1]);
                    edgeStrength += Math.abs(pixels[index + sampleWidth] - pixels[index - sampleWidth]);
                    edgeSamples += 2;
                }
            }

            previousFramePixels = pixels;

            return {
                brightness: brightness / pixels.length,
                movement,
                sharpness: edgeSamples ? edgeStrength / edgeSamples : 0,
                isStable: movement <= FRAME_CHANGE_THRESHOLD,
                isSharp: edgeSamples ? (edgeStrength / edgeSamples) >= MINIMUM_SHARPNESS : false
            };
        }

        function waitForOcrOperation(operation, timeout, timeoutMessage) {
            let timeoutId = null;

            return Promise.race([
                operation,
                new Promise((_, reject) => {
                    timeoutId = window.setTimeout(() => reject(new Error(timeoutMessage)), timeout);
                })
            ]).finally(() => {
                if (timeoutId !== null) window.clearTimeout(timeoutId);
            });
        }

        function reportOcrProgress({ status, progress }) {
            if (!status) return;

            const percent = Number.isFinite(progress) ? ` ${Math.round(progress * 100)}%` : '';
            if (status === 'recognizing text') {
                setStatus(`Membaca teks halaman${percent}.`);
                return;
            }

            if (status.includes('loading') || status.includes('initializing')) {
                setStatus(`Menyiapkan pembaca teks${percent}. Koneksi internet diperlukan saat pertama kali digunakan.`);
            }
        }

        async function getWorker() {
            if (worker) return worker;

            setStatus('Menyiapkan pembaca teks. Koneksi internet diperlukan saat pertama kali digunakan.');
            worker = await waitForOcrOperation(
                Tesseract.createWorker('ind+eng', 1, { logger: reportOcrProgress }),
                OCR_START_TIMEOUT,
                'Pembaca teks terlalu lama dimuat. Periksa koneksi internet lalu coba pindai lagi.'
            );
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
                .replace(/^([ \t]*)([\u2022\u25CF\u25CB\u25E6\u25AA\u25AB\u25A0\u25A1\u25C6\u25C7])\s*/gm, '$1\u2022 ')
                .replace(/^([ \t]*)(\d+)\s*[\)\]]\s*/gm, '$1$2. ')
                .replace(/^([ \t]*)([A-Za-z])\s*[\)\]]\s*/gm, '$1$2. ')
                .trim();
        }

        function createOcrCandidate(result) {
            const text = normalizeOcrText(result.data.text);
            const compactText = text.replace(/\s/g, '');
            const letters = (compactText.match(/[A-Za-zÀ-ÿ]/g) ?? []).length;
            const words = text.match(/[A-Za-zÀ-ÿ]{2,}/g) ?? [];
            const confidence = Number(result.data.confidence) || 0;
            const recognizedWords = Array.isArray(result.data.words) ? result.data.words : [];
            const wordConfidences = recognizedWords
                .map((word) => Number(word.confidence))
                .filter((wordConfidence) => Number.isFinite(wordConfidence));
            const wordConfidence = wordConfidences.length
                ? wordConfidences.reduce((total, value) => total + value, 0) / wordConfidences.length
                : confidence;
            const letterRatio = compactText.length ? letters / compactText.length : 0;
            const score = (confidence * 0.75)
                + (wordConfidence * 0.15)
                + Math.min(25, letters / 10)
                + Math.min(15, words.length * 1.5)
                + (letterRatio * 10);

            return {
                text,
                confidence,
                wordConfidence,
                letters,
                words: words.length,
                letterRatio,
                score
            };
        }

        function isUsefulOcrCandidate(candidate) {
            if (candidate.text.length < MINIMUM_TEXT_LENGTH
                || candidate.letters < MINIMUM_LETTER_COUNT
                || candidate.words < 1) {
                return false;
            }

            return candidate.confidence >= MINIMUM_OCR_CONFIDENCE
                && candidate.wordConfidence >= MINIMUM_WORD_CONFIDENCE
                && candidate.letterRatio >= 0.35;
        }

        async function recognizeBookPage(ocrWorker, frame) {
            const paragraphResult = await waitForOcrOperation(
                ocrWorker.recognize(frame),
                OCR_RECOGNITION_TIMEOUT,
                'Pembacaan teks terlalu lama. Coba luruskan halaman atau pindai lagi.'
            );
            const paragraphCandidate = createOcrCandidate(paragraphResult);

            // Satu kali OCR biasanya cukup untuk foto halaman yang baik. Threshold
            // adaptif yang mahal hanya dipakai apabila hasil pertama memang buruk.
            if (isUsefulOcrCandidate(paragraphCandidate)) {
                return paragraphCandidate;
            }

            // Fallback khusus dokumen berbayang: threshold lokal memutihkan kertas
            // secara adaptif, lalu mode otomatis memisahkan blok teks halaman.
            const adaptiveFrame = createAdaptiveDocumentCanvas(frame);
            await ocrWorker.setParameters({ tessedit_pageseg_mode: '3' });
            const documentResult = await waitForOcrOperation(
                ocrWorker.recognize(adaptiveFrame),
                OCR_RECOGNITION_TIMEOUT,
                'Pembacaan teks terlalu lama. Coba luruskan halaman atau pindai lagi.'
            );
            await ocrWorker.setParameters({ tessedit_pageseg_mode: '6' });
            const documentCandidate = createOcrCandidate(documentResult);

            return documentCandidate.score > paragraphCandidate.score
                ? documentCandidate
                : paragraphCandidate;
        }

        function startRealtimeTextRecognition() {
            if (!stream || realtimeRecognitionActive || textHeld) return;

            realtimeRecognitionActive = true;
            resetFrameStability();
            scheduleRealtimeTextRecognition(150);
        }

        function stopRealtimeTextRecognition() {
            realtimeRecognitionActive = false;

            if (realtimeScanTimer !== null) {
                window.clearTimeout(realtimeScanTimer);
                realtimeScanTimer = null;
            }

            resetFrameStability();
        }

        function scheduleRealtimeTextRecognition(delay = REALTIME_SCAN_DELAY) {
            if (!realtimeRecognitionActive || !stream || textHeld) return;

            realtimeScanTimer = window.setTimeout(async () => {
                realtimeScanTimer = null;
                await recognizeTextInRealtime();
                scheduleRealtimeTextRecognition();
            }, delay);
        }

        function shouldReplaceDetectedText(candidate, force = false) {
            if (force || !lastDetectedText) return true;
            if (candidate.text === lastDetectedText) return candidate.score >= lastDetectedScore;

            // Jangan mengganti hasil yang baik dengan pembacaan yang sedikit lebih buruk.
            return candidate.score >= lastDetectedScore + 6
                && candidate.confidence >= lastDetectedConfidence - 1;
        }

        async function scanPage({
            force = false,
            preferPhoto = false,
            photoFile = null,
            preparedFrame = null
        } = {}) {
            if (!stream || textHeld || scanning || video.readyState < 2) return;

            scanning = true;
            setScanRequestPending(false);
            refreshCameraToggleButton();
            setCameraScanState('reading');

            try {
                const ocrWorker = await getWorker();
                const frame = preparedFrame ?? await captureBestOcrFrame({ preferPhoto, photoFile });
                const candidate = await recognizeBookPage(ocrWorker, frame);

                if (!stream || textHeld || textEditedManually) return;

                if (!isUsefulOcrCandidate(candidate)) {
                    if (lastRecognitionState !== 'unclear') {
                        announceScanProgress('Teks belum terdeteksi. Pastikan halaman terang, lurus, dan stabil, lalu pindai lagi.', 'error');
                        lastRecognitionState = 'unclear';
                    }
                    return;
                }

                if (!shouldReplaceDetectedText(candidate, force)) {
                    if (candidate.text === lastDetectedText) {
                        lastDetectedScore = Math.max(lastDetectedScore, candidate.score);
                        lastDetectedConfidence = Math.max(lastDetectedConfidence, candidate.confidence);
                    }
                    return;
                }

                textArea.value = candidate.text;
                lastDetectedText = candidate.text;
                lastDetectedScore = candidate.score;
                lastDetectedConfidence = candidate.confidence;
                lastRecognitionState = 'detected';
                announceScanProgress('Halaman siap. Teks berhasil terdeteksi. Tekan dua kali tombol Bacakan teks untuk membacakan.', 'detected');
            } catch (error) {
                console.error(error);
                const message = error?.message || 'Deteksi teks tidak dapat dijalankan.';
                if (message.includes('terlalu lama')) {
                    worker?.terminate?.();
                    worker = null;
                }
                announceScanProgress(`${message} Periksa koneksi internet, lalu coba pindai lagi.`, 'error');
            } finally {
                scanning = false;
                setCameraScanState(stream ? 'ready' : 'waiting');
                refreshCameraToggleButton();
            }
        }

        async function recognizeTextInRealtime() {
            if (!stream || !realtimeRecognitionActive || textHeld || scanning || video.readyState < 2) return;

            const quality = inspectFrameQuality();
            if (!quality) return;

            if (quality.brightness < DARKNESS_THRESHOLD) {
                steadyFrameSamples = 0;
                setCameraScanState('waiting');
                if (lastRecognitionState !== 'dark') {
                    setStatus('Cahaya kurang. Tambahkan pencahayaan agar tulisan terbaca.', 'error');
                    lastRecognitionState = 'dark';
                }
                return;
            }

            if (!quality.isStable) {
                steadyFrameSamples = 0;
                setCameraScanState('waiting');
                if (lastRecognitionState !== 'moving') {
                    setStatus('Tahan kamera tetap stabil di atas halaman buku.');
                    lastRecognitionState = 'moving';
                }
                return;
            }

            if (!quality.isSharp) {
                steadyFrameSamples = 0;
                setCameraScanState('waiting');
                if (lastRecognitionState !== 'blurred') {
                    setStatus('Teks masih buram. Dekatkan kamera atau sentuh layar untuk memfokuskan kamera.', 'error');
                    lastRecognitionState = 'blurred';
                }
                return;
            }

            steadyFrameSamples += 1;
            setCameraScanState(steadyFrameSamples >= REQUIRED_STEADY_FRAME_SAMPLES ? 'ready' : 'steady');

            if (steadyFrameSamples < REQUIRED_STEADY_FRAME_SAMPLES || textEditedManually) {
                return;
            }

            if (lastRecognitionState !== 'ready') {
                setStatus('Halaman siap dipindai. Ketuk dua kali tombol Pindai halaman.');
                lastRecognitionState = 'ready';
            }
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

            isReadingText = true;
            const currentSpeechSession = ++speechSession;
            window.speechSynthesis.cancel();

            const speech = new SpeechSynthesisUtterance(makeTextSpeakable(text));
            configureIndonesianVoice(speech);

            speech.rate = Number(rate.value);
            speech.onstart = () => setStatus('Bacara sedang membacakan teks.');
            speech.onend = () => {
                if (currentSpeechSession !== speechSession) return;

                isReadingText = false;
                setStatus('Pembacaan selesai.');
            };
            speech.onerror = () => {
                if (currentSpeechSession !== speechSession) return;

                isReadingText = false;
                setStatus('Suara tidak dapat diputar. Coba lagi.', 'error');
            };

            window.speechSynthesis.speak(speech);
        }

        function refreshHoldTextButton() {
            holdTextButton.textContent = textHeld ? 'Lanjutkan teks' : 'Tahan teks';
            holdTextButton.setAttribute('aria-pressed', String(textHeld));
        }

        function setTextHeld(shouldHold) {
            if (textHeld === shouldHold) return;

            textHeld = shouldHold;
            refreshHoldTextButton();

            if (textHeld) {
                stopRealtimeTextRecognition();
                setStatus('Hasil teks ditahan. Teks tidak akan berubah sampai Anda melanjutkannya.');
                return;
            }

            lastDetectedText = '';
            lastDetectedScore = 0;
            lastDetectedConfidence = 0;
            lastRecognitionState = '';
            textEditedManually = false;
            setStatus('Pemindaian halaman dilanjutkan. Ketuk dua kali tombol Pindai halaman saat halaman siap.');
            startRealtimeTextRecognition();
        }

        function toggleTextHold() {
            setTextHeld(!textHeld);
        }

        function stopSpeech() {
            speechSession += 1;
            isReadingText = false;

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }

            setStatus('Pembacaan dihentikan.');
        }

        function refreshText() {
            stopSpeech();
            textArea.value = '';
            lastDetectedText = '';
            lastDetectedScore = 0;
            lastDetectedConfidence = 0;
            lastRecognitionState = '';
            textEditedManually = false;
            setStatus(textHeld
                ? 'Hasil teks dikosongkan dan tetap ditahan.'
                : 'Hasil teks dikosongkan. Siapkan halaman lalu ketuk dua kali tombol Pindai halaman.');
        }

        async function scanCurrentPage() {
            if (textHeld || textEditedManually) {
                announceScanProgress('Lanjutkan atau refresh hasil teks terlebih dahulu sebelum memindai halaman baru.', 'error');
                return;
            }

            if (!stream || video.readyState < 2) {
                announceScanProgress('Kamera belum siap. Tunggu sebentar lalu pindai lagi.', 'error');
                return;
            }

            setScanRequestPending(true);
            announceScanProgress('Memindai halaman.');

            // Jangan melakukan await sebelum membuka input file pada web biasa.
            // Browser Android dapat mencabut user activation setelah await, lalu
            // photoCaptureInput.click() tidak lagi diizinkan membuka kamera.
            const androidOcr = getAndroidOcrPlugin();
            if (androidOcr) {
                try {
                    await scanWithAndroidOcr(androidOcr);
                } finally {
                    setScanRequestPending(false);
                }
                return;
            }

            resetFrameStability();
            lastAutoScanAt = Date.now();

            if (!('ImageCapture' in window)) {
                // Dipanggil langsung dari ketukan kedua agar browser tetap
                // menganggapnya sebagai aksi pengguna dan membuka kamera.
                photoCaptureInput.value = '';
                photoCaptureInput.click();
                return;
            }

            try {
                const photoFrame = await takeHighResolutionPhoto();

                if (photoFrame) {
                    announceScanProgress('Halaman terdeteksi. Memproses halaman baru.');
                    await scanPage({ force: true, preparedFrame: photoFrame });
                    return;
                }

                // Jangan kembali ke input file di sini: operasi asinkron di atas
                // sudah dapat membuat browser menolak pembukaan kamera. Gunakan
                // frame video yang aktif sebagai fallback yang selalu tersedia.
                announceScanProgress('Halaman terdeteksi. Memproses halaman baru.');
                await scanPage({ force: true });
            } catch (error) {
                console.error(error);
                announceScanProgress('Halaman tidak dapat dipindai. Pastikan kamera tetap aktif lalu coba lagi.', 'error');
            } finally {
                if (!scanning) setScanRequestPending(false);
            }
        }

        function getAndroidOcrPlugin() {
            if (!window.Capacitor?.isNativePlatform?.()) return null;

            return window.Capacitor.Plugins?.BacaraOcr ?? null;
        }

        async function scanWithAndroidOcr(androidOcr = getAndroidOcrPlugin()) {
            if (!androidOcr) return false;

            const cameraWasActive = stream !== null;
            let finalMessage = '';
            let finalMode = 'info';
            if (cameraWasActive) stopCamera();

            try {
                setStatus('Membuka pemindai dokumen Android. Luruskan halaman lalu ambil foto.');
                const result = await androidOcr.scanDocument();
                const text = normalizeOcrText(result?.text ?? '');

                if (!text) {
                    finalMessage = 'Tidak ada teks yang terbaca. Luruskan halaman, pastikan cahaya cukup, lalu coba lagi.';
                    finalMode = 'error';
                    setStatus(finalMessage, finalMode);
                    return true;
                }

                textArea.value = text;
                lastDetectedText = text;
                lastDetectedConfidence = 100;
                lastDetectedScore = 250;
                lastRecognitionState = 'detected';
                textEditedManually = false;
                finalMessage = 'Teks diperbarui oleh OCR Android resolusi penuh.';
                finalMode = 'detected';
                setStatus(finalMessage, finalMode);
                return true;
            } catch (error) {
                if (error?.message === 'Pemindaian dibatalkan.') {
                    finalMessage = 'Pemindaian dibatalkan.';
                } else {
                    console.error(error);
                    finalMessage = 'OCR Android tidak dapat dijalankan. Coba izinkan kamera lalu pindai lagi.';
                    finalMode = 'error';
                }
                setStatus(finalMessage, finalMode);
                return true;
            } finally {
                if (cameraWasActive) {
                    await startCamera();
                }
                if (finalMessage) setStatus(finalMessage, finalMode);
            }
        }

        function attachDoubleActivation(button, getAnnouncement, action) {
            let activationTimer = null;

            button.addEventListener('click', () => {
                if (activationTimer !== null) {
                    window.clearTimeout(activationTimer);
                    activationTimer = null;
                    action();
                    return;
                }

                activationTimer = window.setTimeout(() => {
                    activationTimer = null;
                    speakMessage(getAnnouncement());
                }, DOUBLE_ACTIVATION_DELAY);
            });
        }

        async function playInitialGuidance() {
            if (initialGuidancePlayed) return;

            initialGuidancePlayed = true;
            await waitForIndonesianVoice();
            const guidance = 'Selamat datang di Bacara. Jika tersedia, gunakan headset agar suara lebih jelas. Bersihkan lensa kamera, letakkan satu halaman buku di dalam bingkai, lalu arahkan kamera lurus ke halaman dan tahan perangkat dengan stabil. Bacara memindai otomatis ketika gambar sudah stabil dan tajam. Tombol Pindai halaman mengambil foto resolusi penuh untuk hasil terbaik. Jika kamera mendukungnya, tombol Senter tetap berada di kiri atas. Ketuk sekali sebuah tombol untuk mendengar fungsinya. Ketuk cepat dua kali untuk menjalankannya. Tombol Tahan teks ada di kanan atas untuk menjaga hasil agar tidak berubah.';
            setStatus('Panduan penggunaan sedang dibacakan.');
            speakMessage(guidance);
        }

        attachDoubleActivation(
            speakButton,
            () => 'Tombol Bacakan teks. Ketuk cepat dua kali untuk membacakan hasil teks.',
            speakText
        );
        attachDoubleActivation(
            cameraToggleButton,
            () => stream
                ? 'Tombol Matikan kamera. Ketuk cepat dua kali untuk mematikan kamera.'
                : 'Tombol Aktifkan kamera. Ketuk cepat dua kali untuk mengaktifkan kamera.',
            toggleCamera
        );
        attachDoubleActivation(
            scanNowButton,
            () => 'Tombol Pindai halaman. Ketuk cepat dua kali untuk mengambil foto resolusi penuh dan membaca teks halaman dengan kualitas terbaik.',
            scanCurrentPage
        );
        attachDoubleActivation(
            stopSpeechButton,
            () => 'Tombol Hentikan suara. Ketuk cepat dua kali untuk menghentikan pembacaan.',
            stopSpeech
        );
        attachDoubleActivation(
            refreshTextButton,
            () => 'Tombol Refresh. Ketuk cepat dua kali untuk mengosongkan hasil teks.',
            refreshText
        );
        attachDoubleActivation(
            holdTextButton,
            () => textHeld
                ? 'Tombol Lanjutkan teks. Ketuk cepat dua kali agar hasil dipindai kembali ketika kamera stabil.'
                : 'Tombol Tahan teks. Ketuk cepat dua kali agar hasil teks tidak berubah.',
            toggleTextHold
        );
        attachDoubleActivation(
            torchToggleButton,
            () => torchEnabled
                ? 'Tombol Matikan senter. Ketuk cepat dua kali untuk mematikan senter kamera.'
                : 'Tombol Nyalakan senter. Ketuk cepat dua kali untuk menyalakan senter kamera.',
            toggleTorch
        );

        rate.addEventListener('input', () => {
            rateValue.textContent = Number(rate.value).toFixed(1);
        });

        textArea.addEventListener('input', () => {
            textEditedManually = true;
            setTextHeld(true);
        });

        photoCaptureInput.addEventListener('change', async () => {
            const photoFile = photoCaptureInput.files?.[0];
            photoCaptureInput.value = '';
            setScanRequestPending(false);

            if (!photoFile) {
                setStatus('Pengambilan foto dibatalkan.');
                return;
            }

            if (textHeld || textEditedManually) return;

            resetFrameStability();
            lastAutoScanAt = Date.now();
            announceScanProgress('Halaman terdeteksi. Memproses halaman baru.');
            await scanPage({ force: true, photoFile });
        });

        window.addEventListener('pagehide', () => {
            stopCamera();
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }

            if (worker) {
                worker.terminate();
            }
        });

        refreshCameraToggleButton();
        refreshHoldTextButton();

        window.addEventListener('load', () => {
            window.setTimeout(playInitialGuidance, 250);
            window.setTimeout(startCamera, 900);
        }, { once: true });
    </script>
</body>
</html>

