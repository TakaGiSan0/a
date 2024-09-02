<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
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
        $response = $this->get('/superadmin/employee'); // Permintaan GET ke halaman yang di-redirect
        $response->assertStatus(200);

        


    }
}
