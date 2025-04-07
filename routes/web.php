<?php

use App\Http\Controllers\RunController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pest\Support\View;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::get('/dashboard', function() {
    $user = Auth::user();
    $recentRuns = $user->runs()->latest('date')->take(5)->get();
    $totalRuns = $user->runs()->count();
    $totalDistance = $user->runs()->sum('distance');
    $totalDuration = $user->runs()->sum('duration');

    return view('dashboard', compact('recentRuns', 'totalRuns', 'totalDistance', 'totalDuration'));

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('/runs/track', function() {
        return view('runs.track');
    })->name('runs.track');

    Route::resource('runs', RunController::class);
});

require __DIR__.'/auth.php';
