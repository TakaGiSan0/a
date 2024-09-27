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
    

    

    /**
     * Show the form for creating a new resource.
     */

    
    
    /**
     * Update the specified resource in storage.
     */
    
    /**
     * Remove the specified resource from storage.
     */
    

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
