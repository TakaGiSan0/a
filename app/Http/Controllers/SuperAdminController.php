<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\training_record;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\peserta;
use Barryvdh\DomPDF\Facade\pdf;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');

        $training_records = training_record::query()
            ->when($searchQuery, function ($query, $searchQuery) {
                $query->where('training_name', 'like', "%{$searchQuery}%");
            })
            ->paginate(10);

        return view('superadmin.index', compact('training_records'));
    }

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

        return view('superadmin.form', compact('categories', 'peserta'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {
        // Validasi data dengan pesan khusus untuk doc_ref yang sudah ada
        $data = $request->validate(
            [
                'training_name' => 'required|string|max:255',
                'doc_ref' => 'required|string|max:255|unique:training_records,doc_ref', // Validasi unik
                'job_skill' => 'required|string|max:255',
                'trainer_name' => 'required|string|max:255',
                'rev' => 'required|string|max:255',
                'station' => 'required|string|max:255',
                'training_date' => 'required|date',
                'skill_code' => 'required|string|max:255',
                'category_id' => 'required|integer|exists:categories,id',
                'participants.*.badge_no' => 'required|string|max:255',
                'participants.*.employee_name' => 'required|string|max:255',
                'participants.*.dept' => 'required|string|max:255',
                'participants.*.position' => 'required|string|max:255',
                'participants.*.level' => 'required|string|max:255',
                'participants.*.final_judgement' => 'required|string|max:255',
                'participants.*.license' => 'nullable|string|max:255',
                'participants.*.theory_result' => 'required|string|max:255',
                'participants.*.practical_result' => 'required|string|max:255',
            ],
            [
                'doc_ref.unique' => 'Pelatihan sudah ada.', // Pesan khusus untuk doc_ref
            ],
        );

        $status = $request->has('save_as_draft') ? 'pending' : 'completed';

        // Simpan data pelatihan utama
        $trainingRecord = Training_Record::create([
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

        if ($status === 'completed') {
            $participantsToAttach = [];
            foreach ($data['participants'] as $participant) {
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
        session(['pending_participants' => $data['participants']]);

        return redirect()->route('superadmin.dashboard')->with('success', 'Training records berhasil dibuat!');
    }

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
        $trainingRecords = training_record::with(['trainingCategory:id,name']);
        $data = $request->all();
        $status = $request->has('save_as_draft') ? 'pending' : 'completed';

        $trainingRecord = Training_Record::findOrFail($id);
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

        if ($status === 'completed') {
            // Update data peserta
            $trainingRecord->pesertas()->detach();
            foreach ($data['participants'] as $participant) {
                $peserta = Peserta::where('badge_no', $participant['badge_no'])->first();
                if ($peserta) {
                    $trainingRecord->pesertas()->attach($peserta->id, [
                        'level' => $participant['level'],
                        'final_judgement' => $participant['final_judgement'],
                        'license' => $participant['license'],
                        'theory_result' => $participant['theory_result'],
                        'practical_result' => $participant['practical_result'],
                    ]);
                }
            }
        }

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

    public function search(Request $request)
    {
        $query = Training_Record::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('training_name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dept') && $request->dept != '') {
            $query->where('dept', $request->dept);
        }

        if ($request->has('station') && $request->station != '') {
            $query->where('station', $request->station);
        }

        if ($request->has('training_date') && $request->training_date != '') {
            $query->whereDate('training_date', $request->training_date);
        }

        if ($request->has('training_category') && $request->training_category != '') {
            $query->where('category_id', $request->training_category);
        }

        $results = $query->get();

        return response()->json($results);
    }

    public function generatePDF()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'content' => 'This is some content.',
        ];

        $pdf = PDF::loadView('pdf.test', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }
}
