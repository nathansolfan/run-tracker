<?php

namespace App\Http\Controllers;

use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class RunController extends Controller
{
    // add this to avoid the red line on authorize()
    use AuthorizesRequests;

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

        // For debugging
        \Log::info("Run data received", [
            'has_route_data' => $request->has('route_data'),
            'route_data_length' => $request->has('route_data') ? strlen($request->route_data) : 0
        ]);



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


        // Process route_data if present
        if (!empty($validated['route_data'])) {
            try {
                //this validation will ensure its valid JSON
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Invalid JSON in route_data: ' . json_last_error_msg());
                }
                // The model will handle the JSON-to-array conversion via the casting
            } catch (\Exception $e) {
                \Log::error('Error processing route_data: ' . $e->getMessage());
            }            
        }

        $run = Auth::user()->runs()->create($validated);

            // Check if request is AJAX/JSON


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
    public function edit(Run $run)
    {
        $this->authorize('update', $run);
        return view('runs.edit', compact('run') );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Run $run)
    {
        $this->authorize('update', $run);

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

        // UPDATE the existing run instead of creating a new one
        $run->update($validated);

        // pass ,$run model
        return redirect()->route('runs.show', $run)->with('success', 'Run updated successfully');      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Run $run)
    {
        $this->authorize('delete', $run);
        $run->delete();

        return redirect()->route('runs.index')->with('success', 'Route deleted with success');
    }
}
