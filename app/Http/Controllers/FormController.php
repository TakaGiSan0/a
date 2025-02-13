<?php

namespace App\Http\Controllers;

use App\Models\Escape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\training_record;
use App\Models\category;
use App\Models\attachments;
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
        $years = Training_Record::selectRaw('YEAR(date_start) as year')
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
                $query->whereYear('date_start', $selectedYear);
            })
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderBy('date_start', 'desc')

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



        // Simpan data pelatihan utama
        $trainingRecord = Training_Record::create([
            'training_name' => $data['training_name'],
            'doc_ref' => $data['doc_ref'],
            'job_skill' => $data['job_skill'],
            'trainer_name' => $data['trainer_name'],
            'rev' => $data['rev'],
            'station' => $data['station'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
            'training_duration' => $data['training_duration'],
            'skill_code' => $data['skill_code'],
            'category_id' => $data['category_id'],
            'attachment' => $filePath,
            'user_id' => auth()->id(),
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
        list($hours, $minutes) = explode(':', $time);
        $minutesInTotal = ($hours * 60) + $minutes;
        $formattedTime = $minutesInTotal;

        $participants =
            $trainingRecord->status === 'Pending'
            ? session('pending_participants', [])
            : $trainingRecord->pesertas;

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

        $status = $request->has('save_as_draft') ? 'pending' : 'completed';

        $trainingRecord = Training_Record::findOrFail($id);

        // Mengambil input menit dari form
        $minutes = $request->input('training_duration'); // misalnya 120

        $hours = floor($minutes / 60);  // Jam
        $remainingMinutes = $minutes % 60;  // Menit sisa

        $formattedTime = sprintf("%d:%02d", $hours, $remainingMinutes);

        $trainingRecord->update([
            'training_name' => $data['training_name'],
            'doc_ref' => $data['doc_ref'],
            'job_skill' => $data['job_skill'],
            'trainer_name' => $data['trainer_name'],
            'rev' => $data['rev'],
            'station' => $data['station'],
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
            'skill_code' => $data['skill_code'],
            'category_id' => $data['category_id'],
            'status' => $status,
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
            'job_skill' => 'required|string|max:255',
            'trainer_name' => 'required|string|max:255',
            'rev' => 'required|string|max:255',
            'station' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'training_duration' => 'required|integer',
            'skill_code' => 'required|string|max:255',
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
}
