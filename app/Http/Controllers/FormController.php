<?php

namespace App\Http\Controllers;

use App\Models\Escape;
use App\Models\product_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\training_record;
use App\Models\category;
use App\Models\peserta;
use App\Models\training_skill;
use App\Models\training_comment;

use Barryvdh\DomPDF\Facade\pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


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
        $years = Training_Record::selectRaw('YEAR(date_end) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ambil role user
        $user = auth('')->user();

        // Query training_records dengan filter pencarian, tahun, dan byUserRole
        $training_records = Training_Record::with('latestComment')
            ->byUserRole($user)
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
        $product_code = product_code::select('id', 'product_code', 'product_name', 'status')->get();


        return view('dashboard.index', compact('training_records', 'years', 'searchQuery', 'selectedYear', 'jobskill', 'product_code'));
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

        if (strtotime($data['date_start']) > strtotime($data['date_end'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_start' => 'Start date tidak boleh lebih besar dari End date.']);
        }

        $skillCodes = $request->input('skill_codes', []);

        // Ambil ID dari skill_code yang sesuai
        $trainingSkillIds = Training_Skill::whereIn('skill_code', $skillCodes)->pluck('id')->toArray();


        $filePath = null; // Inisialisasi path file untuk penyimpanan
        if ($request->hasFile('attachment')) {
            $pdfFile = $request->file('attachment');

            // Buat nama file unik untuk menghindari konflik
            $fileName = str_replace(' ', '+', $pdfFile->getClientOriginalName());

            try {
                $filePath = $pdfFile->storeAs('attachment', $fileName, 'public');
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

        $trainingRecord->comments()->create([
            'comment' => null,
            'approval' => 'Pending',
        ]);

        // Simpan data Training Skill
        $trainingRecord->training_Skills()->sync($trainingSkillIds);

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
                        'evaluation' => $participant['evaluation'],
                        'theory_result' => $participant['theory_result'],
                        'practical_result' => $participant['practical_result'],
                    ]);
                }

                // Ambil ID dari pivot (hasil_peserta)
                $hasilPeserta = DB::table('hasil_peserta')
                    ->where('peserta_id', $peserta->id)
                    ->where('training_record_id', $trainingRecord->id)
                    ->latest('id')
                    ->first();

                // Jika evaluation bernilai 1, simpan ke tabel training_evaluation
                if ($participant['evaluation'] == '1' && $hasilPeserta) {
                    DB::table('training_evaluation')->insert([
                        'hasil_peserta_id' => $hasilPeserta->id,
                        'created_at' => now(),
                        'updated_at' => now(),
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
        $record = Training_Record::select('status', 'user_id', 'attachment')->with('user')->findOrFail($id);

        $history = training_comment::where('training_record_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($history->isEmpty()) {
            Log::info('History tidak ditemukan untuk ID: ' . $id);
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Ambil record terakhir dari history
        $lastComment = $history->last();

        $pathToFile = $record->attachment; // <-- Gunakan nama kolom yang benar dari $record

        $attachmentUrl = $pathToFile
            ? asset("storage/" . ($pathToFile))
            : null;

        return response()->json([
            'history' => $history,
            'comment' => $lastComment->comment,
            'approval' => $lastComment->approval,
            'status' => $record->status,
            'attachment' => $attachmentUrl,
            'requestor_name' => $record->user->user ?? 'Unknown User',
            'created_at' => $record->created_at?->format('d M Y H:i'),
            'updated_at' => $record->updated_at?->format('d M Y H:i'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trainingRecord = Training_Record::with([
            'pesertas',
            'training_skills' => function ($query) {
                $query->withTrashed(); // <-- Ini kuncinya
            }
        ])->findOrFail($id);
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
        $skill_code = $trainingRecord->training_skills;


        // Ambil semua categories
        $categories = Category::all();

        // Kirim data ke view
        return view('form.edit', compact('trainingRecord', 'categories', 'participants', 'formattedTime', 'skill_code'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $data = $request->validate($this->validationRules());
        $trainingRecord = Training_Record::findOrFail($id);

        $currentAttachmentPath = $trainingRecord->attachment; // Simpan path attachment saat ini

        if ($request->hasFile('attachment')) {
            $pdfFile = $request->file('attachment');

            $originalName = $pdfFile->getClientOriginalName();
            $fileName = str_replace(' ', '+', $originalName); // Sesuai logika Anda sebelumnya

            try {
                // 3b. Simpan file baru
                // $filePath akan berisi sesuatu seperti 'attachments/namafile.pdf'
                $newFilePath = $pdfFile->storeAs('attachment', $fileName, 'public');

                // 3c. Jika file baru berhasil disimpan DAN ada file lama, hapus file lama
                if ($newFilePath && $currentAttachmentPath) {
                    if (Storage::disk('public')->exists($currentAttachmentPath)) {
                        Storage::disk('public')->delete($currentAttachmentPath);
                        Log::info('File lama berhasil dihapus: ' . $currentAttachmentPath);
                    } else {
                        Log::warning('File lama tidak ditemukan untuk dihapus: ' . $currentAttachmentPath);
                    }
                }
                $trainingRecord->attachment = $newFilePath; // 3d. Update path di record dengan yang baru

            } catch (\Exception $e) {
                Log::error('File upload error saat update: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengunggah file baru. Silakan coba lagi.');
            }
        }
        $skillCodes = $request->input('skill_codes', []);

        // Ambil ID dari skill_code yang sesuai
        $trainingSkillIds = Training_Skill::whereIn('skill_code', $skillCodes)->pluck('id')->toArray();
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
            'attachment' => $trainingRecord->attachment,
            'training_duration' => $formattedTime
        ]);

        $trainingRecord->training_Skills()->sync($trainingSkillIds);


        $pesertaToSync = [];
        $participantsForEvaluation = []; // Simpan data untuk training_evaluation

        foreach ($data['participants'] as $participant) {
            $peserta = Peserta::where('badge_no', $participant['badge_no'])->first();

            if ($peserta) {
                // Siapkan data untuk sync
                $pesertaToSync[$peserta->id] = [
                    'level' => $participant['level'],
                    'final_judgement' => $participant['final_judgement'],
                    'license' => $participant['license'],
                    'evaluation' => $participant['evaluation'],
                    'theory_result' => $participant['theory_result'],
                    'practical_result' => $participant['practical_result'],
                ];

                // Jika evaluation = 1, tandai peserta ini untuk diproses nanti
                if ($participant['evaluation'] == '1') {
                    $participantsForEvaluation[] = $peserta->id;
                }
            }
        }

        // Lakukan sinkronisasi! Ini akan menambah, update, atau menghapus
        // sesuai kebutuhan agar cocok dengan $pesertaToSync.
        $trainingRecord->pesertas()->sync($pesertaToSync);

        // --- Menangani training_evaluation SETELAH sync ---

        // 1. Ambil semua hasil_peserta yang relevan (yang baru saja disinkronkan
        //    dan memiliki evaluation == 1)
        $relevantHasilPeserta = DB::table('hasil_peserta')
            ->where('training_record_id', $trainingRecord->id)
            ->whereIn('peserta_id', $participantsForEvaluation) // Hanya yang evaluation = 1
            ->get();

        // 2. Hapus dulu entri training_evaluation lama yang mungkin sudah tidak valid
        //    (Ini opsional, tergantung kebutuhan. Jika Anda ingin mempertahankan
        //     status lama, lewati langkah ini atau buat logikanya lebih kompleks)
        DB::table('training_evaluation')
            ->whereIn('hasil_peserta_id', $trainingRecord->pesertas()->pluck('hasil_peserta.id'))
            ->delete(); // Hati-hati dengan ini, pastikan ini yang Anda mau.

        // 3. Masukkan entri baru untuk yang relevan
        foreach ($relevantHasilPeserta as $hasil) {
            // Anda bisa tambahkan pengecekan di sini jika tidak ingin duplikat
            // atau jika Anda tidak menghapus semua di langkah 2.
            DB::table('training_evaluation')->insert([
                'hasil_peserta_id' => $hasil->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Redirect atau response dengan pesan sukses
        return redirect()->route('dashboard.index')->with('success', 'Training succesfully updated.');
    }

    public function updateComment(Request $request, $id)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:255',
            'approval' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        // Cari Training_Record berdasarkan ID
        $trainingRecord = Training_Record::findOrFail($id);

        // Perbarui status di tabel training_record
        $trainingRecord->status = $validated['status'];
        $trainingRecord->save();

        // Buat entri komentar baru di tabel training_comment
        $comment = training_comment::create([
            'training_record_id' => $trainingRecord->id,
            'approval' => $validated['approval'],
            'comment' => $validated['comment'],

        ]);


        return redirect()->back()->with('success', 'Comment Succesfully Updated.');
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

            'attachment' => 'required|file|mimes:pdf|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'participants.*.badge_no' => 'max:255',
            'participants.*.employee_name' => 'max:255',
            'participants.*.dept' => 'max:255',
            'participants.*.position' => 'max:255',
            'participants.*.level' => 'max:255',
            'participants.*.final_judgement' => 'max:255',
            'participants.*.license' => 'nullable|max:255',
            'participants.*.evaluation' => 'nullable|max:255',
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

    public function jobs_skill_store(Request $request)
    {
        $request->validate([
            'job_skill' => 'required|string|max:255',
            'skill_code' => 'required|string|max:100',
        ]);

        training_skill::create([
            'job_skill' => $request->job_skill,
            'skill_code' => $request->skill_code,
        ]);

        return redirect()->back()->with('success', 'Job Skill successfully created');
    }

    public function jobs_skill_destroy($id)
    {
        $trainingSkill = Training_Skill::find($id);

        if (!$trainingSkill) {
            return redirect()->route('dashboard.index')->with('error', 'Skill tidak ditemukan.');
        }

        // Melakukan soft delete
        $trainingSkill->delete(); // Ini akan mengisi kolom `deleted_at`

        return redirect()->route('dashboard.index')->with('success', 'Job Skill Succesfully Deleted. (soft deleted).');
    }

    public function product_code_store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:255',
            'product_name' => 'required|string|max:100',
        ]);

        product_code::create([
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
        ]);

        return redirect()->back()->with('success', 'Product Code successfully created');
    }

    public function product_update(Request $request, $id)
    {
        $product = product_code::find($id);

        if (!$product) {
            return redirect()->route('dashboard.index')->with('error', 'Product code not found.');
        }

        // Ambil status tujuan dari form
        $newStatus = $request->input('status');

        // Update status
        $product->status = $newStatus;
        $product->save();

        return redirect()->route('dashboard.index')->with('success', 'Product status updated to ' . $newStatus . '.');
    }


    public function getJobSkill($skillCode)
    {
        $skill = Training_Skill::where('skill_code', $skillCode)->first();

        if ($skill) {
            return response()->json([
                'job_skill' => $skill->job_skill,
                'id' => $skill->id
            ]);
        }

        return response()->json([
            'job_skill' => null,
            'id' => null
        ], 404);
    }
}
