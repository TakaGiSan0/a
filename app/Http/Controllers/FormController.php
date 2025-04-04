<?php

namespace App\Http\Controllers;

use App\Models\Escape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\training_record;
use App\Models\category;
use App\Models\peserta;
use App\Models\training_skill;
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
        $searchQuery = $request->input('search');
        $selectedYear = $request->input('year');

        // Ambil tahun unik dari date_start
        $years = Training_Record::selectRaw('YEAR(date_start) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ambil role user
        $user = auth('web')->user();

        // Query training_records dengan filter pencarian, tahun, dan byUserRole
        $training_records = Training_Record::query()
            ->byUserRole($user) // Tambahkan filter berdasarkan role
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('training_name', 'like', "%{$searchQuery}%");
            })
            ->when($selectedYear, function ($query, $selectedYear) {
                return $query->whereYear('date_start', $selectedYear);
            })
            ->orderByRaw("
            CASE 
                WHEN status = 'Waiting Approval' THEN 0 
                WHEN status = 'Pending' THEN 1 
                ELSE 2 
            END
        ")
            ->orderBy('date_start', 'desc')
            ->orderBy('date_end', 'desc')
            ->paginate(10);

        $jobskill = training_skill::select('id', 'job_skill', 'skill_code')->get();

        return view('dashboard.index', compact('training_records', 'years', 'searchQuery', 'selectedYear', 'jobskill'));
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


        if (auth()->guard()->user()->role === 'Super Admin') {
            $status = 'Pending';
        } elseif (auth()->guard()->user()->role === 'Admin') {
            $status = 'Waiting Approval';
        }


        $filePath = null; // Inisialisasi path file untuk penyimpanan
        if ($request->hasFile('attachment')) {
            $pdfFile = $request->file('attachment');

            // Buat nama file unik untuk menghindari konflik
            $fileName = str_replace(' ', '+', $pdfFile->getClientOriginalName());

            try {
                $filePath = $pdfFile->storeAs('attachments', $fileName, 'public');
                Log::info('File berhasil disimpan: ' . $filePath);
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
            }
        }

        // Mengambil input menit dari form
        $minutes = $data['training_duration']; // misalnya 120

        $hours = floor($minutes / 60);  // Jam
        $remainingMinutes = $minutes % 60;  // Menit sisa

        $formattedTime = sprintf("%d:%02d", $hours, $remainingMinutes);

        // Simpan data pelatihan utama
        $trainingRecord = Training_Record::create([
            'training_name' => $data['training_name'],
            'doc_ref' => $data['doc_ref'],

            'trainer_name' => $data['trainer_name'],
            'rev' => $data['rev'],
            'station' => $data['station'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
            'training_duration' => $formattedTime,

            'category_id' => $data['category_id'],
            'status' => $status,
            'attachment' => $filePath,
            'user_id' => auth('web')->id(),
        ]);

        // Simpan data peserta
        $participants = $data['participants'] ?? [];

        if (!empty($participants)) {
            foreach ($participants as $participant) {
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

        // Lanjut ke proses berikutnya tanpa terpengaruh apakah ada peserta atau tidak


        return redirect()->route('dashboard.index')->with('success', 'Training successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment = training_record::select('comment', 'approval', 'status', 'attachment')->where('id', $id)->first();

        if (!$comment) {
            Log::info('Data tidak ditemukan untuk ID: ' . $id);
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $attachmentUrl = $comment->attachment
            ? asset("storage/" . urlencode($comment->attachment))
            : null;

        return response()->json([
            'comment' => $comment->comment,
            'approval' => $comment->approval,
            'status' => $comment->status,
            'attachment' => $attachmentUrl,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trainingRecord = Training_Record::with('pesertas')->findOrFail($id);

        $time = $trainingRecord->training_duration;

        if (!empty($time) && str_contains($time, ':')) {
            $parts = explode(':', $time);
            if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                list($hours, $minutes) = $parts;
                $minutesInTotal = ($hours * 60) + $minutes;
                $formattedTime = $minutesInTotal;
            } else {
                // Jika format tidak sesuai
                $formattedTime = 0;
            }
        } else {
            // Jika training_duration kosong
            $formattedTime = 0;
        }

        $participants = $trainingRecord->pesertas;

        // Ambil semua categories
        $categories = Category::all();

        // Kirim data ke view
        return view('form.edit_completed', compact('trainingRecord', 'categories', 'participants', 'formattedTime'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $data = $request->validate($this->validationRules());
        $trainingRecord = Training_Record::findOrFail($id);

        // Mengambil input menit dari form
        $minutes = $request->input('training_duration'); // misalnya 120

        $hours = floor($minutes / 60);  // Jam
        $remainingMinutes = $minutes % 60;  // Menit sisa

        $formattedTime = sprintf("%d:%02d", $hours, $remainingMinutes);

        $trainingRecord->update([
            'training_name' => $data['training_name'],
            'doc_ref' => $data['doc_ref'],

            'trainer_name' => $data['trainer_name'],
            'rev' => $data['rev'],
            'station' => $data['station'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],

            'category_id' => $data['category_id'],
            'training_duration' => $formattedTime
        ]);


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

            'trainer_name' => 'required|string|max:255',
            'rev' => 'required|string|max:255',
            'station' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'training_duration' => 'required|integer',

            'attachment' => 'file|mimes:pdf|max:2048',
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

    public function jobs_skill_store(request $request)
    {
        $request->validate([
            'job_skill.*' => 'required|string|max:255',
            'skill_code.*' => 'required|string|max:100',
        ]);

        foreach ($request->job_skill as $index => $job_skill) {
            training_skill::create([
                'job_skill' => $job_skill,
                'skill_code' => $request->skill_code[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Job Skill berhasil ditambahkan!');
    }

    public function jobs_skill_destroy($id)
    {
        $jobSkill = training_skill::find($id);

        if (!$jobSkill) {
            return redirect()->back()->with('error', 'Job Skill tidak ditemukan!');
        }

        $jobSkill->delete();
        return redirect()->back()->with('success', 'Job Skill berhasil dihapus!');
    }
}
