<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrialController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/*
    Front door - display list of trials currently taking entries
*/
Route::get('/', [EntryController::class, 'showTrialList'])->name('triallist');

Route::get('entries/user_details/{id}', [EntryController::class, 'getUserDetails']);

Route::post('entries/userdata', [EntryController::class, 'showUserData']);
Route::get('entries/userdata', [EntryController::class, 'userdata']);

Route::patch('/entries/update/{id}', [EntryController::class, 'updateEntry']);

Route::patch('/entries/update/{id}', function (){
    dump(request()->all());
    dd("stop");
});

Route::get('entries/saveddata', [EntryController::class, 'showSavedData']);

Route::get('/entries/entrylist', [EntryController::class, 'list']);

Route::get('/entries/create/{trialid}', [EntryController::class, 'create'])->name('entries.create');

Route::get('/entries/create_another', [EntryController::class, 'create_another'])->name('entries.create_another');

Route::post('/entries/store', [EntryController::class, 'store']);
Route::post('/entry/store', [EntryController::class, 'store']);

Route::get('entries/delete/{id}', [EntryController::class, 'delete']);

Route::get('/entries/edit/{entry}', [EntryController::class, 'edit'])->name('entries.edit');


Route::get('/', [TrialController::class, 'showTrialList'])->name('triallist');




Route::post('checkout', [EntryController::class, 'checkout']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
