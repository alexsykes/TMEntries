<?php

require __DIR__ . '/auth.php';

use App\Http\Controllers\EntryController;
use App\Http\Controllers\Http\Controller;
use App\Http\Controllers\ProfileController;

//use App\Http\Controllers\WebhookEndpointController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Models\User;
use Laravel\Cashier\Cashier;


/*
    Front door - display list of trials currently taking entries
*/
Route::get('/', [TrialController::class, 'showTrialList'])->name('triallist');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
 * TRIAL Routes
 */

Route::get('/adminTrials', [TrialController::class, 'adminTrials'])->middleware(['auth', 'verified'])->name('adminTrials');
Route::get('trials/edit/{id}', [TrialController::class, 'edit'])->middleware(['auth', 'verified'])->name('edit');

// Public
Route::get('/trials', [TrialController::class, 'showTrialList'])->name('trials');
Route::get('trial/details/{trial_id}', [TrialController::class, 'details'])->name('details');
Route::get('trial/{trial_id}/entrylist', [TrialController::class, 'entryList'])->name('entrylist');

Route::get('trials/toggleVisibility/{id}', [TrialController::class, 'toggleVisibility'])->middleware(['auth', 'verified'])->name('toggleVisibility');
Route::get('trials/add', [TrialController::class, 'add'])->middleware(['auth', 'verified'])->name('add');
Route::get('trials/remove/{id}', [TrialController::class, 'remove'])->middleware(['auth', 'verified'])->name('remove');
Route::patch('trials/update', [TrialController::class, 'update'])->middleware(['auth', 'verified'])->name('update');
Route::post('trials/store', [TrialController::class, 'store'])->middleware(['auth', 'verified'])->name('store');
Route::post('trials/edit/saveasnew', [TrialController::class, 'saveasnew'])->middleware(['auth', 'verified'])->name('saveasnew');


/*
ENTRY Routes
*/// Entry gateway -

Route::get('/entries/userdata/{trialid}', [EntryController::class, 'userdata'])->middleware(['auth', 'verified'])->name('userdata');
Route::patch('/entries/userupdate', [EntryController::class, 'userupdate']);

Route::get('/entry/withdraw/{id}', [EntryController::class, 'withdraw']);


Route::get('/entries/edit/{entry}', [EntryController::class, 'edit'])->middleware('auth', 'verified')->name('entries.edit');
Route::get('/entries/delete/{id}', [EntryController::class, 'delete'])->middleware('auth', 'verified')->name('entries.delete');

Route::get('entries/user_details/{id}', [EntryController::class, 'getUserDetails']);
Route::get('/adminEntries', [EntryController::class, 'adminEntries'])->middleware(['auth', 'verified'])->name('adminEntries');

Route::get('/entry/useredit', [EntryController::class, 'useredit']);

Route::post('entries/userdata', [EntryController::class, 'showUserData']);

Route::patch('/entries/update/{id}', [EntryController::class, 'updateEntry']);
Route::get('entries/saveddata', [EntryController::class, 'showSavedData']);
Route::get('/entries/entrylist', [EntryController::class, 'list']);
Route::get('/entries/create/{trialid}', [EntryController::class, 'create'])->name('entries.create');
//Route::get('/entries/create_another', [EntryController::class, 'create_another'])->name('entries.create_another');
Route::post('/entries/store', [EntryController::class, 'store']);
Route::post('/entry/store', [EntryController::class, 'store']);


Route::post('/entries/createSession', [EntryController::class, 'createStripeSession']);

/*
 * VENUE Routes
 */
Route::get('/venues', [VenueController::class, 'list'])->name('venues');
Route::get('/venues/add', [VenueController::class, 'add']);
Route::get('/venues/edit/{$venueID}', [VenueController::class, 'edit']);
Route::post('/venues/add', [VenueController::class, 'store']);
Route::post('/venues/update', [VenueController::class, 'update']);

// Stripe Routes
Route::post('/stripe/checkout', [StripePaymentController::class, 'stripeCheckout']);
Route::get('/checkout/success', [StripePaymentController::class, 'checkoutSuccess'])->name('checkout-success');
Route::view('/checkout/cancel', 'checkout.cancel')->name('checkout-cancel');
Route::post('/entries/checkout', [EntryController::class, 'checkout']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
