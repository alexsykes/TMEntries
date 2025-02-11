<?php

use App\Console\Http\Controllers\EntryController;
use App\Console\Http\Controllers\ProfileController;
use App\Console\Http\Controllers\TrialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/entries/create/{trialid}', [EntryController::class, 'create'])->name('entries.create');

Route::get('/entries/create_another', [EntryController::class, 'create_another'])->name('entries.create_another');

Route::post('entries/store', [EntryController::class, 'store']);

Route::get('/entries/{entry}/edit', [EntryController::class, 'edit'])->name('entries.edit');

Route::get('/trial_list', [TrialController::class, 'showTrialList'])->name('triallist');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
