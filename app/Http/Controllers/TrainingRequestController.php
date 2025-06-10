<?php

namespace App\Http\Controllers;

use App\Models\TrainingRequest;
use Illuminate\Http\Request;

class TrainingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = TrainingRequest::with('peserta')
            ->byUserRole(auth('')->user())  // pastikan user yg login diteruskan
            ->paginate(10);

        return view('request.index', compact('request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:1000',
        ]);

        $user = auth("")->user();
        $peserta = $user->pesertaLogin;

        if (!$peserta) {
            return back()->with('error', 'Data peserta tidak ditemukan untuk user ini.');
        }

        TrainingRequest::create([
            'user_id_login' => $user->id,
            'peserta_id' => $peserta->id,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Training Request Successfully Created');
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
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trainingRequest = TrainingRequest::findOrFail($id);

        $trainingRequest->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->back() // Ganti 'training_requests.index' sesuai nama route Anda
                         ->with('success', 'Training Request Successfully Deleted');
    
    }
}
