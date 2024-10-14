<?php

namespace App\Http\Controllers;

use App\Models\Escape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\training_record;
use App\Models\category;
use App\Models\peserta;
use Barryvdh\DomPDF\Facade\pdf;


class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');

        // Ambil tahun unik dari kolom training_date
        $years = Training_Record::selectRaw('YEAR(training_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $training_records = training_record::query()
            ->when($searchQuery, function ($query, $searchQuery) {
                $query->where('training_name', 'like', "%{$searchQuery}%");
            })
            ->orderBy('training_date', 'asc')
            ->paginate(10);

        $userRole = auth('')->user()->role;

        return view("dashboard.index", compact('training_records', 'years', 'searchQuery'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Dapatkan role user yang sedang login
        $userRole = auth('')->user()->role;

        // Cek apakah role adalah 'superadmin' atau 'admin'
        if (!in_array($userRole, ['super admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Cache::remember('categories', 60 * 60, function () {
            return category::all(); // Simpan di cache selama 1 jam
        });
        $peserta = Cache::remember('peserta', 60 * 60, function () {
            return peserta::all(); // Simpan di cache selama 1 jam
        });

        return view('form.form', compact('categories', 'peserta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->validationRules(), $this->validationMessages());
        $status = $request->has('save_as_draft') ? 'pending' : 'completed';

        $trainingRecord = $this->createTrainingRecord($data, $status);
        $this->attachParticipants($trainingRecord, $data['participants'], $status);

        session(['pending_participants' => $data['participants']]);

        return $this->redirectAfterSave(auth('')->user()->role);
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data training record berdasarkan ID
        $trainingRecord = Training_Record::findOrFail($id);

        // Ambil data peserta jika form statusnya draft
        $participants =
            $trainingRecord->status === 'Pending'
            ? session('pending_participants', []) // Ambil data dari session
            : $trainingRecord->pesertas; // Jika bukan pending, ambil dari database

        // Ambil semua categories
        $categories = Category::all();

        // Kirim data ke view
        return view('superadmin.edit_completed', compact('trainingRecord', 'categories', 'participants'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $status = $request->has('save_as_draft') ? 'pending' : 'completed';

        $trainingRecord = Training_Record::findOrFail($id);
        $this->updateTrainingRecord($trainingRecord, $data, $status);
        $this->attachParticipants($trainingRecord, $data['participants'], $status);

        return redirect()->route('superadmin.dashboard')->with('success', 'Training record berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trainingRecord = training_record::findOrFail($id);

        // Menggunakan Policy untuk memeriksa izin
        $this->authorize('delete', $trainingRecord);

        // Hapus data trainingRecord
        $trainingRecord->delete();

        // Redirect atau response dengan pesan sukses
        return redirect()->route('superadmin.dashboard')->with('success', 'Peserta berhasil dihapus.');
    }

    private function validationRules()
    {
        return [
            'training_name' => 'required|string|max:255',
            'doc_ref' => 'required|string|max:255|unique:training_records,doc_ref',
            'job_skill' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:255',
            'rev' => 'required|string|max:255',
            'station' => 'required|string|max:255',
            'training_date' => 'required|date',
            'skill_code' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'participants.*.badge_no' => 'max:255',
            'participants.*.employee_name' => 'max:255',
            'participants.*.dept' => 'max:255',
            'participants.*.position' => 'max:255',
            'participants.*.level' => 'max:255',
            'participants.*.final_judgement' => 'max:255',
            'participants.*.license' => 'nullable|max:255',
            'participants.*.theory_result' => 'max:255',
            'participants.*.practical_result' => 'max:255',
        ];
    }

    private function validationMessages()
    {
        return [
            'doc_ref.unique' => 'Pelatihan sudah ada.',
        ];
    }

    private function createTrainingRecord(array $data, string $status)
    {
        return Training_Record::create([
            'training_name' => $data['training_name'],
            'doc_ref' => $data['doc_ref'],
            'job_skill' => $data['job_skill'],
            'trainer_name' => $data['trainer_name'],
            'rev' => $data['rev'],
            'station' => $data['station'],
            'training_date' => $data['training_date'],
            'skill_code' => $data['skill_code'],
            'category_id' => $data['category_id'],
            'status' => $status,
        ]);
    }

    private function updateTrainingRecord($trainingRecord, array $data, string $status)
    {
        $trainingRecord->update([
            'training_name' => $data['training_name'],
            'doc_ref' => $data['doc_ref'],
            'job_skill' => $data['job_skill'],
            'trainer_name' => $data['trainer_name'],
            'rev' => $data['rev'],
            'station' => $data['station'],
            'training_date' => $data['training_date'],
            'skill_code' => $data['skill_code'],
            'category_id' => $data['category_id'],
            'status' => $status,
        ]);
    }

    private function attachParticipants($trainingRecord, array $participants, string $status)
    {
        $participantsToAttach = [];

        if ($status === 'completed') {
            foreach ($participants as $participant) {
                $peserta = Peserta::where('badge_no', $participant['badge_no'])->first();
                if ($peserta) {
                    $participantsToAttach[$peserta->id] = [
                        'level' => $participant['level'],
                        'final_judgement' => $participant['final_judgement'],
                        'license' => $participant['license'],
                        'theory_result' => $participant['theory_result'],
                        'practical_result' => $participant['practical_result'],
                    ];
                }
            }
        }

        $trainingRecord->pesertas()->attach($participantsToAttach);
    }

    private function redirectAfterSave(string $userRole)
    {
        switch ($userRole) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('success', 'Training records berhasil dibuat!');
            case 'super admin':
                return redirect()->route('superadmin.dashboard')->with('success', 'Training records berhasil dibuat!');
            default:
                abort(403, 'Unauthorized action.');
        }
    }
}
