<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Projects\ProjectDetail;
Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    
    // Naya Route Project Detail ke liye
    Route::get('/projects/{project}', ProjectDetail::class)->name('projects.show');
});
