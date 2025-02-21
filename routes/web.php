<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;
use function Pest\Laravel\get;


/*
    Front door - display list of trials currently taking entries
*/
Route::get('/', [TrialController::class, 'showTrialList'])->name('triallist');


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/venues', function () {
    return view('venues');
})->name('venues');

Route::get('/trials', [TrialController::class, 'showTrialList'])->name('trials');
Route::get('/adminTrials', [TrialController::class, 'adminTrials'])->name('adminTrials');
Route::get('trials/details/{id}', [TrialController::class, 'details'])->name('details');


Route::get('entries/user_details/{id}', [EntryController::class, 'getUserDetails']);

Route::post('entries/userdata', [EntryController::class, 'showUserData']);
Route::get('entries/userdata', [EntryController::class, 'userdata']);

Route::patch('/entries/update/{id}', [EntryController::class, 'updateEntry']);



Route::get('entries/saveddata', [EntryController::class, 'showSavedData']);

Route::get('/entries/entrylist', [EntryController::class, 'list']);

Route::get('/entries/create/{trialid}', [EntryController::class, 'create'])->name('entries.create');

Route::get('/entries/create_another', [EntryController::class, 'create_another'])->name('entries.create_another');

Route::post('/entries/store', [EntryController::class, 'store']);
Route::post('/entry/store', [EntryController::class, 'store']);
Route::get('entries/delete/{id}', [EntryController::class, 'delete']);
Route::get('/entries/edit/{entry}', [EntryController::class, 'edit'])->name('entries.edit');


Route::get('/venues/add', [VenueController::class, 'create']);
Route::get('/venues/edit/{$venueID}', [VenueController::class, 'edit']);
Route::post('/venues/add', [VenueController::class, 'store']);
Route::post('/venues/update', [VenueController::class, 'update']);


Route::get('/showAdminTrialsList', [TrialController::class, 'showAdminTrialsList'])->name('triallist');




Route::post('checkout', [EntryController::class, 'checkout']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
