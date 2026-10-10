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
            ->assertSee('const REALTIME_SCAN_DELAY = 300', false)
            ->assertSee('const MINIMUM_OCR_CONFIDENCE = 26', false)
            ->assertSee('const OCR_START_TIMEOUT = 45000', false)
            ->assertSee('const VIDEO_OCR_TARGET_WIDTH = 2048', false)
            ->assertSee('function optimizeCameraForText', false)
            ->assertSee('function recognizeBookPage', false)
            ->assertSee('function configureIndonesianVoice', false);
    }

    public function test_camera_page_provides_hold_and_double_activation_controls(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('id="refresh-text"', false)
            ->assertSee('Refresh</button>', false)
            ->assertSee('id="hold-text"', false)
            ->assertSee('Tahan teks</button>', false)
            ->assertSee('function attachDoubleActivation', false)
            ->assertSee('function waitForOcrOperation', false)
            ->assertSee('function reportOcrProgress', false)
            ->assertSee('photoCaptureInput.click();', false)
            ->assertSee('const MINIMUM_TEXT_LENGTH = 6', false)
            ->assertSee('gunakan headset agar suara lebih jelas', false)
            ->assertSee('Ketuk sekali sebuah tombol untuk mendengar fungsinya.', false)
            ->assertSee('const DOUBLE_ACTIVATION_DELAY = 450', false)
            ->assertDontSee('textArea.focus()', false);
    }
}

