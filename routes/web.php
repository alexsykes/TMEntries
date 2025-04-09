<?php

require __DIR__ . '/auth.php';

use App\Http\Controllers\EntryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScoringController;

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

//Route::get('/', function () {
//    return view('trials.trial_list');
//});
Route::get('dashboard', [TrialController::class, 'showTrialList'])->name('dashboard');


// CLUB access
Route::get('clubaccess', [TrialController::class, 'adminTrials'])->middleware(['auth', 'verified']);
// ADMIN access
Route::get('adminaccess', [\App\Http\Controllers\AdminController::class, 'userList'])->middleware(['auth', 'verified']);

/*
 * TRIAL Routes
 */

Route::get('adminTrials', [TrialController::class, 'adminTrials'])->middleware(['auth', 'verified'])->name('adminTrials');

Route::get('/trials/adminEntryList/{id}', [TrialController::class, 'adminEntryList'])->middleware(['auth', 'verified']);
Route::get('/admin/entry/edit/{id}', [EntryController::class, 'adminEdit'])->middleware(['auth', 'verified']);
Route::get('/admin/entry/cancel/{id}', [EntryController::class, 'adminCancel'])->middleware(['auth', 'verified']);
Route::get('/admin/entries/editRidingNumbers/{id}', [EntryController::class, 'editRidingNumbers'])->middleware(['auth', 'verified']);
Route::patch('/admin/entries/update', [EntryController::class, 'adminEntryUpdate'])->middleware(['auth', 'verified']);
Route::post('/admin/entries/store', [EntryController::class, 'adminEntryStore'])->middleware(['auth', 'verified']);
Route::get('/admin/entries/delete/{id}', [EntryController::class, 'adminEntryDelete'])->middleware(['auth', 'verified']);
Route::get('admin/entries/printSignOnSheets/{id}', [EntryController::class, 'printSignOnSheets'])->middleware(['auth', 'verified']);
//

Route::get('/trials/edit/{id}', [TrialController::class, 'edit'])->middleware(['auth', 'verified'])->name('edit');

// Public
Route::get('/trials', [TrialController::class, 'showTrialList'])->name('trials');
Route::get('trial/details/{trial_id}', [TrialController::class, 'details'])->name('details');
Route::get('trial/{trial_id}/entrylist', [TrialController::class, 'entryList'])->name('entrylist');

Route::get('trials/toggleVisibility/{id}', [TrialController::class, 'toggleVisibility'])->middleware(['auth', 'verified'])->name('toggleVisibility');
Route::get('trials/remove/{id}', [TrialController::class, 'remove'])->middleware(['auth', 'verified'])->name('remove');
Route::patch('trials/update', [TrialController::class, 'update'])->middleware(['auth', 'verified'])->name('update');
Route::post('trials/store', [TrialController::class, 'store'])->middleware(['auth', 'verified'])->name('store');
Route::post('trials/save', [TrialController::class, 'save'])->middleware(['auth', 'verified'])->name('save');
Route::post('trials/edit/saveasnew', [TrialController::class, 'saveasnew'])->middleware(['auth', 'verified'])->name('saveasnew');


Route::get('trials/add', [TrialController::class, 'add'])->middleware(['auth', 'verified'])->name('add');
Route::get('trials/addTrialDetail/{id}', [TrialController::class, 'addTrialTrial'])->middleware(['auth', 'verified']);
Route::get('trials/addTrialEntry/{id}', [TrialController::class, 'addTrialEntry'])->middleware(['auth', 'verified']);
Route::get('trials/addTrialScoring/{id}', [TrialController::class, 'addTrialScoring'])->middleware(['auth', 'verified']);
Route::get('trials/addTrialRegs/{id}', [TrialController::class, 'addTrialRegs'])->middleware(['auth', 'verified']);
Route::get('trials/addTrialFees/{id}', [TrialController::class, 'addTrialFees'])->middleware(['auth', 'verified']);


/*
ENTRY Routes
*/// Entry gateway -
Route::get('/userEntryList', [EntryController::class, 'userEntryList'])->middleware(['auth', 'verified'])->name('userEntryList');
Route::get('/entries/register/{trialid}', [EntryController::class, 'register'])->middleware(['auth', 'verified']);
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
Route::post('/entries/saveRidingNumbers', [EntryController::class, 'saveRidingNumbers']);


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

// SCORING routes
Route::get('/scores/setup/{id}', [ScoringController::class, 'setup'])->name('scores.setup');
Route::post('/scores/setup', [ScoringController::class, 'setupscoregrid'])->name('scores.setupgrid');
Route::get('/scores/grid/{id}', [ScoringController::class, 'grid'])->name('scores.grid');
Route::get('/scores/section/{id}', [ScoringController::class, 'section'])->name('scores.section');
Route::get('/scores/sectionScoresForRider/{trialid}/{rider}/{section}', [ScoringController::class, 'sectionScoresForRider'])->name('scores.sectionScoreForRider');
Route::get('/scores/sectionScores/{id}/{section}', [ScoringController::class, 'sectionScores'])->name('scores.sectionScores');

Route::patch('/scores/updateSectionScores', [ScoringController::class, 'updateSectionScores'])->name('scores.updateSectionScores');
Route::post('/scores/updateSectionScoreForRider', [ScoringController::class, 'updateSectionScoreForRider'])->name('scores.updateSectionScoreForRider');

// RESULT Routes
Route::get('/results/list', [ResultController::class, 'list'])->name('results.list');
Route::get('/results/display/{id}', [ResultController::class, 'display'])->name('results.display.id');

// MIDDLEWARE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
