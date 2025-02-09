<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/entries/create/{trialid}', [EntryController::class, 'create'])->middleware('auth', 'verified')->name('entries.create');


Route::get('/entries/{entry}/edit', [EntryController::class, 'edit'])->middleware('auth', 'verified')->name('entries.edit');

Route::get('/trial_list', [TrialController::class, 'showTrialList'])->middleware(['auth', 'verified'])->name('triallist');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
