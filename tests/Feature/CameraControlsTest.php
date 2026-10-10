<?php

namespace Tests\Feature;

use Tests\TestCase;

class CameraControlsTest extends TestCase
{
    public function test_camera_page_provides_accessible_real_time_camera_controls(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertHeader('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()')
            ->assertSee('id="camera-toggle"', false)
            ->assertSee('Aktifkan kamera</button>', false)
            ->assertSee('Matikan kamera', false)
            ->assertDontSee('id="start-camera"', false)
            ->assertDontSee('id="scan"', false)
            ->assertDontSee('id="auto-scan"', false)
            ->assertSee('tesseract.js', false)
            ->assertSee('startRealtimeTextRecognition()', false)
            ->assertSee('const REALTIME_SCAN_DELAY = 500', false)
            ->assertSee('const MINIMUM_OCR_CONFIDENCE = 26', false)
            ->assertSee('const OCR_START_TIMEOUT = 45000', false)
            ->assertSee('const VIDEO_OCR_TARGET_WIDTH = 2048', false)
            ->assertSee('function optimizeCameraForText', false)
            ->assertSee('function recognizeBookPage', false)
            ->assertSee('function warmOcrWorker', false)
            ->assertSee('const PHOTO_OCR_TARGET_WIDTH = 2400', false)
            ->assertSee('function announceScanProgress', false)
            ->assertSee('Halaman siap dipindai. Ketuk dua kali tombol Pindai halaman.', false)
            ->assertSee('photo-capture', false)
            ->assertSee('preserve_interword_spaces: \'0\'', false)
            ->assertSee('function configureIndonesianVoice', false);
    }

    public function test_camera_page_locks_results_until_refresh_and_uses_double_activation_controls(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('id="refresh-text"', false)
            ->assertSee('Refresh</button>', false)
            ->assertDontSee('id="hold-text"', false)
            ->assertSee('let textLocked = false', false)
            ->assertSee('Ketuk dua kali tombol Refresh untuk memindai halaman baru.', false)
            ->assertSee('id="torch-toggle"', false)
            ->assertSee('speak-dock-wrap', false)
            ->assertSee('bottom: calc(100% + 10px)', false)
            ->assertSee('function toggleTorch', false)
            ->assertSee('function attachDoubleActivation', false)
            ->assertSee('function waitForOcrOperation', false)
            ->assertSee('function reportOcrProgress', false)
            ->assertSee('takeHighResolutionPhoto()', false)
            ->assertSee('photoCaptureInput.click();', false)
            ->assertSee("announceScanProgress('Mengambil foto halaman resolusi tinggi.')", false)
            ->assertSee('Foto halaman terdeteksi. Memproses teks asli.', false)
            ->assertSee('Halaman siap. Teks berhasil terdeteksi.', false)
            ->assertSee('const MINIMUM_TEXT_LENGTH = 6', false)
            ->assertSee('gunakan headset agar suara lebih jelas', false)
            ->assertSee('Ketuk sekali sebuah tombol untuk mendengar fungsinya.', false)
            ->assertSee('const DOUBLE_ACTIVATION_DELAY = 450', false)
            ->assertDontSee('textArea.focus()', false);
    }
}

