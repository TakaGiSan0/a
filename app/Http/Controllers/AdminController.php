<?php /** @noinspection Annotator */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\training_record;

class AdminController extends Controller
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

        return view('admin.index', compact('training_records'));
    }

    public function create()
    {
        // Fungsi Create admin berada di app/Http/Controllers/SuperAdminController.php
        // Bersamaan dengan Role SuperAdmin dikarenakan memiliki fungsi yang sama

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Fungsi Store admin berada di app/Http/Controllers/SuperAdminController.php
        // Bersamaan dengan Role SuperAdmin dikarenakan memiliki fungsi yang sama
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
