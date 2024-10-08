<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\Training_Record;
use App\Models\Category;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;


class Test extends TestCase
{

    public function testIndex()
    {
        $user = User::factory()->create(['role' => 'super admin'])->first();
        
        $response = $this->get('/form');

        $response->assertStatus(200);
        $response->assertViewIs('superadmin.index');
    }

    public function testCreate()
    {
        $user = User::factory()->create(['role' => 'super admin']);
        

        Cache::shouldReceive('remember')->andReturn(Category::factory()->count(3)->create(), Peserta::factory()->count(3)->create());

        $response = $this->get('/form/create');

        $response->assertStatus(200);
        $response->assertViewIs('form.form');
    }

    public function testStore()
    {
        $user = User::factory()->create(['role' => 'super admin']);
        

        $category = Category::factory()->create();
        $peserta = Peserta::factory()->create();

        $data = [
            'training_name' => 'Test Training',
            'doc_ref' => 'DOC123',
            'job_skill' => 'Skill Test',
            'trainer_name' => 'Trainer Test',
            'rev' => 'Rev 1',
            'station' => 'Station Test',
            'training_date' => '2023-10-10',
            'skill_code' => 'SK123',
            'category_id' => $category->id,
            'participants' => [
                [
                    'badge_no' => $peserta->badge_no,
                    'employee_name' => $peserta->employee_name,
                    'dept' => $peserta->dept,
                    'position' => $peserta->position,
                    'level' => 'Level 1',
                    'final_judgement' => 'Pass',
                    'license' => 'License 1',
                    'theory_result' => 'Pass',
                    'practical_result' => 'Pass',
                ],
            ],
        ];

        $response = $this->post('/form', $data);

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseHas('training_records', ['doc_ref' => 'DOC123']);
    }

    public function testEdit()
    {
        $user = User::factory()->create(['role' => 'super admin']);
        

        $trainingRecord = Training_Record::factory()->create();

        $response = $this->get("/form/{$trainingRecord->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('superadmin.edit_completed');
    }

    public function testUpdate()
    {
        $user = User::factory()->create(['role' => 'super admin']);
        

        $trainingRecord = Training_Record::factory()->create();
        $category = Category::factory()->create();
        $peserta = Peserta::factory()->create();

        $data = [
            'training_name' => 'Updated Training',
            'doc_ref' => 'DOC123',
            'job_skill' => 'Updated Skill',
            'trainer_name' => 'Updated Trainer',
            'rev' => 'Rev 2',
            'station' => 'Updated Station',
            'training_date' => '2023-11-11',
            'skill_code' => 'SK124',
            'category_id' => $category->id,
            'participants' => [
                [
                    'badge_no' => $peserta->badge_no,
                    'employee_name' => $peserta->employee_name,
                    'dept' => $peserta->dept,
                    'position' => $peserta->position,
                    'level' => 'Level 2',
                    'final_judgement' => 'Pass',
                    'license' => 'License 2',
                    'theory_result' => 'Pass',
                    'practical_result' => 'Pass',
                ],
            ],
        ];

        $response = $this->put("/form/{$trainingRecord->id}", $data);

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseHas('training_records', ['doc_ref' => 'DOC123', 'training_name' => 'Updated Training']);
    }

    public function testDestroy()
    {
        $user = User::factory()->create(['role' => 'super admin']);
       

        $trainingRecord = Training_Record::factory()->create();

        $response = $this->delete("/form/{$trainingRecord->id}");

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseMissing('training_records', ['id' => $trainingRecord->id]);
    }
}