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
            min-height: 64px;
            box-shadow: 0 4px 16px rgba(7, 55, 47, .28);
        }

        .speak-dock .primary {
            min-width: 0;
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
            body { font-size: 17px; }
            .panel { padding: 16px; }
            main { padding: 10px 14px 40px; }
            .speak-dock { width: calc(100% - 28px); }
            .speak-dock .primary { min-width: 0; flex: 1; }
            .speak-dock button { padding-inline: 12px; }
        }

        @media (orientation: landscape) and (max-height: 600px) {
            .camera, video { min-height: 220px; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#hasil-teks">Langsung ke hasil teks</a>
    <button class="hold-text-button" id="hold-text" type="button" aria-pressed="false">Tahan teks</button>

    <main>
        <h1 class="sr-only">Bacara, pembaca buku melalui kamera</h1>
        <p id="status" class="sr-only" role="status" aria-live="polite" aria-atomic="true" data-mode="info">Panduan suara sedang disiapkan.</p>

        <div class="grid">
            <section class="panel" id="pemindai" aria-labelledby="judul-kamera">
                <h2 id="judul-kamera" class="sr-only">Kamera</h2>

                <div class="camera">
                    <video id="video" autoplay playsinline muted aria-label="Pratinjau kamera buku"></video>
                </div>
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

    <div class="speak-dock" role="group" aria-label="Kontrol kamera dan pembacaan teks">
        <button class="primary" id="baca" type="button" aria-describedby="bantuan-baca">Bacakan teks</button>
        <button id="camera-toggle" type="button" aria-pressed="false">Aktifkan kamera</button>
        <button id="hentikan-suara" type="button">Hentikan suara</button>
        <button id="refresh-text" type="button" aria-label="Refresh hasil teks">Refresh</button>
    </div>
    <p class="sr-only" id="bantuan-baca">Ketuk sekali untuk mendengar fungsi tombol. Ketuk dua kali dengan cepat untuk menjalankannya.</p>

    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@6/dist/tesseract.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const statusBox = document.getElementById('status');
        const textArea = document.getElementById('hasil-teks');
        const speakButton = document.getElementById('baca');
        const cameraToggleButton = document.getElementById('camera-toggle');
        const stopSpeechButton = document.getElementById('hentikan-suara');
        const refreshTextButton = document.getElementById('refresh-text');
        const holdTextButton = document.getElementById('hold-text');
        const rate = document.getElementById('kecepatan');
        const rateValue = document.getElementById('nilai-kecepatan');
        const lightCanvas = document.createElement('canvas');

        let stream = null;
        let cameraStarting = false;
        let worker = null;
        let scanning = false;
        let realtimeScanTimer = null;
        let realtimeRecognitionActive = false;
        let lastDetectedText = '';
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

        const DARKNESS_THRESHOLD = 58;
        const REALTIME_SCAN_DELAY = 5000;
        const MINIMUM_OCR_CONFIDENCE = 50;
        const DOUBLE_ACTIVATION_DELAY = 450;

        function setStatus(message, mode = 'info') {
            statusBox.textContent = message;
            statusBox.dataset.mode = mode;
        }

        function getIndonesianVoice() {
            return window.speechSynthesis
                .getVoices()
                .find((voice) => voice.lang.toLowerCase().startsWith('id'));
        }

        function speakMessage(message) {
            if (!('speechSynthesis' in window)) return;

            speechSession += 1;
            isReadingText = false;
            window.speechSynthesis.cancel();

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
                        width: { ideal: 1280, max: 1280 },
                        height: { ideal: 720, max: 720 },
                        frameRate: { ideal: 24, max: 24 }
                    },
                    audio: false
                });

                video.srcObject = stream;
                await video.play();
                setStatus('Kamera aktif. Teks akan diperbarui setiap lima detik.');
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

            if (stream) {
                stream.getTracks().forEach((track) => track.stop());
                stream = null;
            }

            video.srcObject = null;
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

            const inset = 0.05;
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
            context.imageSmoothingEnabled = true;
            context.imageSmoothingQuality = 'high';
            context.filter = lightLevel !== null && lightLevel < 90
                ? 'grayscale(1) contrast(2) brightness(1.28)'
                : 'grayscale(1) contrast(1.65) brightness(1.08)';
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

            worker = await Tesseract.createWorker('ind+eng', 1);
            await worker.setParameters({
                tessedit_pageseg_mode: '3',
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

        function startRealtimeTextRecognition() {
            if (!stream || realtimeRecognitionActive || textHeld) return;

            realtimeRecognitionActive = true;
            scheduleRealtimeTextRecognition(500);
        }

        function stopRealtimeTextRecognition() {
            realtimeRecognitionActive = false;

            if (realtimeScanTimer !== null) {
                window.clearTimeout(realtimeScanTimer);
                realtimeScanTimer = null;
            }
        }

        function scheduleRealtimeTextRecognition(delay = REALTIME_SCAN_DELAY) {
            if (!realtimeRecognitionActive || !stream || textHeld) return;

            realtimeScanTimer = window.setTimeout(async () => {
                realtimeScanTimer = null;
                await recognizeTextInRealtime();
                scheduleRealtimeTextRecognition();
            }, delay);
        }

        async function recognizeTextInRealtime() {
            if (!stream || !realtimeRecognitionActive || textHeld || scanning || video.readyState < 2) return;

            scanning = true;

            try {
                const ocrWorker = await getWorker();
                const result = await ocrWorker.recognize(captureFrame());
                const detectedText = normalizeOcrText(result.data.text);
                const confidence = Number(result.data.confidence) || 0;

                if (!realtimeRecognitionActive || textHeld || textEditedManually) return;

                if (detectedText.length < 8 || confidence < MINIMUM_OCR_CONFIDENCE) {
                    if (lastRecognitionState !== 'unclear') {
                        setStatus('Teks belum cukup jelas. Bersihkan lensa, ratakan buku, lalu arahkan kamera lebih dekat.', 'error');
                        lastRecognitionState = 'unclear';
                    }
                    return;
                }

                const isBetterReading = confidence >= lastDetectedConfidence - 5;

                if (detectedText !== lastDetectedText && (isBetterReading || !lastDetectedText)) {
                    textArea.value = detectedText;
                    lastDetectedText = detectedText;
                    lastDetectedConfidence = confidence;
                    lastRecognitionState = 'detected';
                    setStatus('Teks diperbarui. Pemindaian berikutnya dilakukan dalam lima detik.', 'detected');
                } else if (detectedText === lastDetectedText) {
                    lastDetectedConfidence = Math.max(lastDetectedConfidence, confidence);
                }
            } catch (error) {
                console.error(error);
                setStatus('Deteksi teks tidak dapat dijalankan. Periksa koneksi internet, lalu aktifkan kamera kembali.', 'error');
                stopRealtimeTextRecognition();
            } finally {
                scanning = false;
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
            lastDetectedConfidence = 0;
            lastRecognitionState = '';
            textEditedManually = false;
            setStatus('Deteksi teks dilanjutkan. Hasil akan diperbarui setiap lima detik.');
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
            lastDetectedConfidence = 0;
            lastRecognitionState = '';
            textEditedManually = false;
            setStatus(textHeld
                ? 'Hasil teks dikosongkan dan tetap ditahan.'
                : 'Hasil teks dikosongkan. Deteksi real-time tetap berjalan.');
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

        function playInitialGuidance() {
            if (initialGuidancePlayed) return;

            initialGuidancePlayed = true;
            const guidance = 'Selamat datang di Bacara. Bersihkan lensa kamera, letakkan buku di tempat yang cukup terang, lalu arahkan kamera lurus ke halaman dan tahan perangkat dengan stabil. Kamera akan membaca teks setiap lima detik. Ketuk sekali sebuah tombol untuk mendengar fungsinya. Ketuk cepat dua kali untuk menjalankannya. Tombol Tahan teks ada di kanan atas untuk menjaga hasil agar tidak berubah.';
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
                ? 'Tombol Lanjutkan teks. Ketuk cepat dua kali agar hasil diperbarui kembali setiap lima detik.'
                : 'Tombol Tahan teks. Ketuk cepat dua kali agar hasil teks tidak berubah.',
            toggleTextHold
        );

        rate.addEventListener('input', () => {
            rateValue.textContent = Number(rate.value).toFixed(1);
        });

        textArea.addEventListener('input', () => {
            textEditedManually = true;
            setTextHeld(true);
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
