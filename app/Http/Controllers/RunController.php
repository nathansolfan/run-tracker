<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RunController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //Retrieves the currently logged-in user's runs
    // Orders them by date (newest first)
    // Paginates them (10 per page)
    // Displays the runs.index view

    public function index() {
        $runs = Auth::user()->runs()->latest('date')->paginate(10);
        return view('runs.index', compact('runs'));
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('runs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'distance' => 'required|numeric|min:0.01',
        'duration' => 'required|string',
        'date' => 'required|date',
        'notes' => 'nullable|string',
        ]);

        // Convert duration input (HH:MM:SS or MM:SS) to seconds
        $durationParts = array_reverse(explode(':', $request->duration));
        $seconds = 0;

        // seconds
        if (isset($durationParts[0])) {
            $seconds += intval($durationParts[0]);
        }

        // minutes
        if (isset($durationParts[1])) {
            $seconds += intval($durationParts[1] * 60);
        }

        // hours
        if (isset($durationParts[2])) {
            $seconds += intval($durationParts[2] * 3600);
        }

        $validated['duration'] = $seconds;

        $run = Auth::user()->runs()->create($validated);

        return redirect()->route('runs.index')->with('success', 'Run logged successfully');


    }

    /**
     * Display the specified resource.
     */
    public function show(Run $run)
    {
        $this->authorize('view', $run);
        return view('runs.show', compact('run'));        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
