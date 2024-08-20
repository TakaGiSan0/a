<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("index.super_admin");
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
    public function show(Escape $escape)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Escape $escape)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Escape $escape)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Escape $escape)
    {
        //
    }
}
