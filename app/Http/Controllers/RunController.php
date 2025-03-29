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
    public function show(string $id)
    {
        //
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
