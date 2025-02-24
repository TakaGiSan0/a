<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;


class EmployeeControllerTest extends TestCase
{

    public function testIndex()
{
    
    $user = User::first();
    $this->actingAs($user);
    $this->withoutExceptionHandling(); // Melihat error detail

    Peserta::factory()->count(5)->create();

    $response = $this->get('/EmployeeTrainingRecord_list?dept[]=IT&dept[]=HR&searchQuery=John');

    dd($response->getContent()); // Melihat response sebelum assertion

    $response->assertStatus(200)->assertViewIs('content.employee')->assertSee('Employee');
}

    public function testShow()
    {
        // Create a Peserta record
        $peserta = Peserta::factory()->create();

        // Call the controller method
        $controller = new EmployeeController();
        $response = $controller->show($peserta->id);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'peserta',
            'grouped_records'
        ]);
    }

    public function testShowNotFound()
    {
        // Call the controller method with a non-existent ID
        $controller = new EmployeeController();
        $response = $controller->show(999);

        // Assert the response
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Peserta not found']);
    }

    public function testDownloadPdf()
    {
        // Create a Peserta record
        $peserta = Peserta::factory()->create();

        // Mock the PDF facade
        Pdf::shouldReceive('loadView')->once()->andReturnSelf();
        Pdf::shouldReceive('download')->once()->andReturn('pdf content');

        // Call the controller method
        $controller = new EmployeeController();
        $response = $controller->downloadPdf($peserta->id);

        // Assert the response
        $this->assertEquals('pdf content', $response->getContent());
    }

    public function testDownloadPdfNotFound()
    {
        // Call the controller method with a non-existent ID
        $controller = new EmployeeController();
        $response = $controller->downloadPdf(999);

        // Assert the response
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Peserta not found']);
    }
}
