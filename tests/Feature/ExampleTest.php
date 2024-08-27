<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function Login(): void
    {
        $postData = [
            'user' => 'super admin',
            'password' => 'superadmin',
        ];

        // Kirim permintaan POST untuk login
        $response = $this->post('/login', $postData);

        // Pastikan status respons adalah 302 (redirect)
        $response->assertStatus(302);

        // Pastikan redirect ke halaman yang benar (misalnya dashboard)
        $response->assertRedirect('/superadmin/dashboard');

        // Ikuti redirect dan pastikan status akhir adalah 200
        $response = $this->get('/superadmin/dashboard'); // Permintaan GET ke halaman yang di-redirect
        $response->assertStatus(200);
    }
}
