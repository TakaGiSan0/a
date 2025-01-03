<?php

namespace App\Http\Controllers;

use App\Models\Escape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\training_record;
use App\Models\category;
use App\Models\peserta;
use Barryvdh\DomPDF\Facade\pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;




class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter pencarian (search) dan tahun dari request
        $searchQuery = $request->input('search');
        $selectedYear = $request->input('year'); // Ambil tahun yang dipilih dari request

        // Ambil tahun unik dari kolom training_date
        $years = Training_Record::selectRaw('YEAR(training_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Query untuk mengambil data training dengan filter tahun dan pencarian
        $training_records = Training_Record::query()
            ->when($searchQuery, function ($query, $searchQuery) {
                // Filter berdasarkan nama training
                $query->where('training_name', 'like', "%{$searchQuery}%");
            })
            ->when($selectedYear, function ($query, $selectedYear) {
                // Filter berdasarkan tahun training_date
                $query->whereYear('training_date', $selectedYear);
            })
            ->orderBy('training_date', 'desc')
            ->paginate(10);

        $userRole = auth('')->user()->role;

        // Kembalikan ke view dengan data yang dibutuhkan
        return view('dashboard.index', compact('training_records', 'years', 'searchQuery', 'selectedYear'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Dapatkan role user yang sedang login
        $userRole = auth('')->user()->role;

        // Cek apakah role adalah 'superadmin' atau 'admin'
        if (!in_array($userRole, ['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $categories = category::all();
        $peserta = peserta::all();

        return view('form.form', compact('categories', 'peserta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate($this->validationRules(), $this->validationMessages());

        // Mengambil file PDF
        if ($request->hasFile('attachment')) {
            // Menyimpan file dengan nama yang unik
            $pdfFile = $request->file('attachment');
            $filePath = $pdfFile->store('attachments', 'public'); // menyimpan di storage/app/public/attachments

            // Menyimpan path file di session atau variabel lain jika diperlukan
            session()->put('pdf_file_path', $filePath);
        }



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
        ]);
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



        return redirect()->route('dashboard.index')->with('success', 'Training succesfully created.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment = training_record::select('comment', 'approval', 'status')->where('id', $id)->first();

        if (!$comment) {
            Log::info('Data tidak ditemukan untuk ID: ' . $id);
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['comment' => $comment->comment, 'approval' => $comment->approval, 'status' => $comment->status]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data training record berdasarkan ID
        // Ambil training record beserta data peserta dan pivot
        $trainingRecord = Training_Record::with('pesertas')->findOrFail($id);


        // Ambil data peserta jika form statusnya draft
        $participants =
            $trainingRecord->status === 'Pending'
            ? session('pending_participants', []) // Ambil data dari session
            : $trainingRecord->pesertas; // Jika bukan pending, ambil dari database

        // Ambil semua categories
        $categories = Category::all();

        // Kirim data ke view
        return view('form.edit_completed', compact('trainingRecord', 'categories', 'participants'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $data = $request->validate($this->validationRules());

        $status = $request->has('save_as_draft') ? 'pending' : 'completed';

        if (auth()->guard()->user()->role === 'Super Admin') {
            $approval = $data['approval'];
        } elseif (auth()->guard()->user()->role === 'Admin') {
            $approval = $request->has('send') ? 'Pending' : 'Completed';
        }
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
            'approval' => $approval,
        ]);
        if ($status === 'completed') {

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

        // Redirect atau response dengan pesan sukses
        return redirect()->route('dashboard.index')->with('success', 'Training succesfully updated.');
    }

    public function updateComment(request $request, $id)
    {

        $validated = $request->validate([
            'comment' => 'required|string|max:255',
            'approval' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $record = Training_Record::findOrFail($id);
        $record->comment = $validated['comment'];
        $record->approval = $validated['approval'];
        $record->status = $validated['status'];
  
        $record->save();

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui.');
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
        return redirect()->route('dashboard.index')->with('success', 'Training succesfully deleted.');
    }

    private function validationRules()
    {
        return [
            'training_name' => 'required|string|max:255',
            'doc_ref' => 'required|string|max:255',
            'job_skill' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:255',
            'rev' => 'required|string|max:255',
            'station' => 'required|string|max:255',
            'training_date' => 'required|date',
            'skill_code' => 'required|string|max:255',
            'attachment' => 'required|max:10240',
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
}
