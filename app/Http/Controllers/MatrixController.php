<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\hasil_peserta;

class MatrixController extends Controller
{
    public function index()
    {  
       
        $matrix =hasil_peserta::where('license', 1)
        ->with('pesertas', 'trainingrecord')
        ->get();
        return view('content.matrix', compact('matrix'));
    }
}
