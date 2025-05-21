<?php

namespace App\Http\Controllers;

use App\Models\TrainingEvaluation;
use Illuminate\Http\Request;

class TrainingEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('evaluation.index');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingEvaluation $trainingEvaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingEvaluation $trainingEvaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingEvaluation $trainingEvaluation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingEvaluation $trainingEvaluation)
    {
        //
    }
}
