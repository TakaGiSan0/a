<?php

use App\Models\training_record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/api/event-number', function () {
    $lastEvent = Event::orderBy('event_number', 'desc')->first();
    $nextEventNumber = $lastEvent ? $lastEvent->event_number + 1 : 1;
    return response()->json(['next_event_number' => $nextEventNumber]);
});
