<?php

namespace Tests\Feature;

use Tests\TestCase;

class CameraAutoActivationTest extends TestCase
{
    public function test_camera_page_announces_automatic_activation(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertHeader('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()')
            ->assertSee('Kamera akan disiapkan otomatis. Izinkan akses jika diminta browser.')
            ->assertSee("document.addEventListener('DOMContentLoaded'", false);
    }
}
