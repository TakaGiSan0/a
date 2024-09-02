<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PesertaTest extends TestCase
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
        $response = $this->get('/superadmin/peserta'); // Permintaan GET ke halaman yang di-redirect
        $response->assertStatus(200);

        $response = $this->get('/superadmin/peserta/create');
        $response->assertStatus(200);

        $peserta = [
            'badge_no' => 'P-6048-18',
            'employee_name' => 'KusnaAli Kudlori',
            'dept' => 'Molding 1',
            'position' => 'Senior Supervisor',
        ];

        // Kirim permintaan POST untuk membuat peserta
        $response = $this->post('/superadmin/peserta/create', $peserta);

        // Pastikan respons adalah 201 (Created)
        $response->assertStatus(201);

        $response = $this->get('/superadmin/peserta/edit/20');
        $response->assertStatus(200);


        $peserta = [
            'badge_no' => 'P-6048-18',
            'employee_name' => 'KusnaAli Kudlori',
            'dept' => 'Molding 1',
            'position' => 'Senior Supervisor',
        ];

        // Kirim permintaan POST untuk membuat peserta
        $response = $this->put('/superadmin/peserta/update/37', $peserta);

        // Pastikan respons adalah 201 (Created)
        $response->assertStatus(200);

    }
}
