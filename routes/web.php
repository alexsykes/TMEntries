<?php

require __DIR__ . '/auth.php';

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ClubmailController ;

use App\Http\Controllers\ClubController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Models\User;
use Laravel\Cashier\Cashier;

//NOTE: wilcards {} should go at end!!!

/*
    Front door - display list of trials currently taking entries
*/
Route::get('/', [TrialController::class, 'showTrialList'])->name('triallist');

//Route::get('/', function () {
//    return view('trials.trial_list');
//});
Route::get('dashboard', [TrialController::class, 'showTrialList'])->name('dashboard');


// CLUB access
Route::get('clubaccess', [TrialController::class, 'adminTrials'])->middleware(['auth', 'verified'])->name('clubaccess');
Route::get('adminTrials', [TrialController::class, 'adminTrials'])->middleware(['auth', 'verified'])->name('adminTrials');
// ADMIN access
Route::get('/adminaccess', [AdminController::class, 'userList'])->middleware(['auth', 'verified']);
Route::get('/admin/users', [AdminController::class, 'userList'])->middleware(['auth', 'verified']);
Route::get('/admin/trials', [AdminController::class, 'trialList'])->middleware(['auth', 'verified']);
Route::get('/admin/results', [AdminController::class, 'resultList'])->middleware(['auth', 'verified']);

/*
 * TRIAL Routes
 */
Route::get('/trial/programme/{id}', [TrialController::class, 'programme']);
Route::get('/trials/adminEntryList/{id}', [TrialController::class, 'adminEntryList'])->middleware(['auth', 'verified']);
Route::get('/admin/entry/edit/{id}', [EntryController::class, 'adminEdit'])->middleware(['auth', 'verified']);
Route::get('/admin/entry/edit/{id}', [EntryController::class, 'adminEdit'])->middleware(['auth', 'verified']);
Route::get('/admin/entry/cancel/{id}', [EntryController::class, 'adminCancel'])->middleware(['auth', 'verified']);
Route::get('/admin/entries/editRidingNumbers/{id}', [EntryController::class, 'editRidingNumbers'])->middleware(['auth', 'verified']);
Route::patch('/admin/entries/update', [EntryController::class, 'adminEntryUpdate'])->middleware(['auth', 'verified']);
Route::post('/admin/entries/store', [EntryController::class, 'adminEntryStore'])->middleware(['auth', 'verified']);
//Route::get('/admin/entries/delete/{id}', [EntryController::class, 'adminEntryDelete'])->middleware(['auth', 'verified']);
Route::get('/admin/entries/printSignOnSheets/{id}', [EntryController::class, 'printSignOnSheets'])->middleware(['auth', 'verified']);
Route::get('/admin/sendMail', [AdminController::class, 'sendMail'])->middleware(['auth', 'verified']);

Route::get('/admin/trial/toggleLock/{id}', [AdminController::class, 'toggleLock'])->middleware(['auth', 'verified']);
Route::get('/admin/trial/toggleEntry/{id}', [AdminController::class, 'toggleEntry'])->middleware(['auth', 'verified']);
Route::get('/admin/trial/toggleScoring/{id}', [AdminController::class, 'toggleScoring'])->middleware(['auth', 'verified']);
Route::get('/admin/trial/toggleResultPublished/{id}', [AdminController::class, 'toggleResultPublished'])->middleware(['auth', 'verified']);
//

Route::get('/trials/edit/{id}', [TrialController::class, 'edit'])->middleware(['auth', 'verified'])->name('edit');

// Public
Route::get('/trials', [TrialController::class, 'showTrialList'])->name('trials');
Route::get('/trial/details/{trial_id}', [TrialController::class, 'details'])->name('details');
Route::get('/trial/{trial_id}/entrylist', [TrialController::class, 'entryList'])->name('entrylist');

Route::get('/trials/toggleVisibility/{id}', [TrialController::class, 'toggleVisibility'])->middleware(['auth', 'verified'])->name('toggleVisibility');
Route::get('/trials/remove/{id}', [TrialController::class, 'remove'])->middleware(['auth', 'verified'])->name('remove');
Route::patch('/trials/update', [TrialController::class, 'update'])->middleware(['auth', 'verified'])->name('update');
Route::post('/trials/store', [TrialController::class, 'store'])->middleware(['auth', 'verified'])->name('store');
Route::post('/trials/save', [TrialController::class, 'save'])->middleware(['auth', 'verified'])->name('save');
Route::post('/trials/edit/saveasnew', [TrialController::class, 'saveasnew'])->middleware(['auth', 'verified'])->name('saveasnew');


Route::get('/trials/add', [TrialController::class, 'add'])->middleware(['auth', 'verified'])->name('add');
Route::get('/trials/addTrialDetail/{id}', [TrialController::class, 'addTrialTrial'])->middleware(['auth', 'verified']);
Route::get('/trials/addTrialEntry/{id}', [TrialController::class, 'addTrialEntry'])->middleware(['auth', 'verified']);
Route::get('/trials/addTrialScoring/{id}', [TrialController::class, 'addTrialScoring'])->middleware(['auth', 'verified']);
Route::get('/trials/addTrialRegs/{id}', [TrialController::class, 'addTrialRegs'])->middleware(['auth', 'verified']);
Route::get('/trials/addTrialFees/{id}', [TrialController::class, 'addTrialFees'])->middleware(['auth', 'verified']);
Route::get('/trials/info/{id}', [TrialController::class, 'info'])->middleware(['auth', 'verified']);


/*
ENTRY Routes
*/// Entry gateway -
Route::get('/userEntryList', [EntryController::class, 'userEntryList'])->middleware(['auth', 'verified'])->name('userEntryList');
Route::get('/entries/register/{trialid}', [EntryController::class, 'register'])->middleware(['auth', 'verified']);
Route::patch('/entries/userupdate', [EntryController::class, 'userupdate']);

Route::get('/entry/withdraw', [EntryController::class, 'withdraw']);
Route::get('/entry/withdrawConfirm/{id}', [EntryController::class, 'withdrawConfirm']);


Route::get('/entries/edit/{entry}', [EntryController::class, 'edit'])->middleware('auth', 'verified')->name('entries.edit');
Route::get('/entries/delete/{id}', [EntryController::class, 'delete'])->middleware('auth', 'verified')->name('entries.delete');

Route::get('entries/user_details/{id}', [EntryController::class, 'getUserDetails']);


Route::get('/entry/useredit', [EntryController::class, 'useredit']);
Route::post('entries/userdata', [EntryController::class, 'showUserData']);

Route::patch('/entries/update/{id}', [EntryController::class, 'updateEntry']);
//Route::get('entries/saveddata', [EntryController::class, 'showSavedData']);
Route::get('/entries/entrylist', [EntryController::class, 'list']);
Route::get('/entries/create/{trialid}', [EntryController::class, 'create'])->name('entries.create');
//Route::get('/entries/create_another', [EntryController::class, 'create_another'])->name('entries.create_another');
Route::post('/entries/store', [EntryController::class, 'store']);
Route::post('/entry/store', [EntryController::class, 'store']);
Route::post('/entries/saveRidingNumbers', [EntryController::class, 'saveRidingNumbers']);
Route::post('/admin/entries/storeMultiple', [EntryController::class, 'storeMultiple'])->middleware(['auth', 'verified']);
Route::get('/otd/{trialid}', [EntryController::class, 'otd_form']);
Route::get('/otd/confirm/{entryid}', [EntryController::class, 'otd_confirm']);
Route::post('/otdCreate', [EntryController::class, 'otdCreate']);
Route::post('/otd/saveNumbers', [EntryController::class, 'otdSaveNumbers']);
Route::get('generate/{trialid}', [EntryController::class, 'generate'])->middleware(['auth', 'verified']);

// Check for usage
//Route::post('/entries/createSession', [EntryController::class, 'createStripeSession']);

/*
 * VENUE Routes
 */
Route::get('/admin/venues', [VenueController::class, 'list'])->name('venues');
Route::get('/venues/add', [VenueController::class, 'add']);
//Route::get('/venues/edit/{$venueID}', [VenueController::class, 'edit']);
Route::get('/venues/edit/{id}', [VenueController::class, 'edit'])->middleware('auth', 'verified')->name('venues.edit');
Route::post('/venues/add', [VenueController::class, 'store']);
//Route::post('/venues/update', [VenueController::class, 'update']);
Route::patch('/venues/save', [VenueController::class, 'save'])->middleware('auth', 'verified')->name('venues.save');

// Stripe Routes
Route::post('/stripe/checkout', [StripePaymentController::class, 'stripeCheckout']);
Route::get('/checkout/success', [StripePaymentController::class, 'checkoutSuccess'])->name('checkout-success');
Route::view('/checkout/cancel', [UserController::class, 'entryList'])->name('checkout-cancel');
Route::post('/entries/checkout', [EntryController::class, 'checkout']);

// SCORING routes
Route::get('/scores/setup/{id}', [ScoringController::class, 'setup'])->name('scores.setup');
Route::post('/scores/setup', [ScoringController::class, 'setupscoregrid'])->name('scores.setupgrid');
Route::get('/scores/grid/{id}', [ScoringController::class, 'grid'])->name('scores.grid');
//Route::get('/scores/section/{id}', [ScoringController::class, 'section'])->name('scores.section');
Route::get('/scores/sectionScoresForRider/{trialid}/{rider}/{section}', [ScoringController::class, 'sectionScoresForRider'])->name('scores.sectionScoreForRider');
Route::get('/scores/sectionScores/{id}/{section}', [ScoringController::class, 'sectionScores'])->name('scores.sectionScores');

Route::patch('/scores/updateSectionScores', [ScoringController::class, 'updateSectionScores'])->name('scores.updateSectionScores');
Route::post('/scores/updateSectionScoreForRider', [ScoringController::class, 'updateSectionScoreForRider'])->name('scores.updateSectionScoreForRider');
Route::post('/scores/confirmPublish', [ScoringController::class, 'confirmPublish'])->name('scores.confirmPublish');
Route::post('/scores/publish', [ScoringController::class, 'publish'])->name('scores.publish');

// USER Routes
Route::post('/user/checkout',[UserController::class, 'checkout']);
Route::get('/close-my-account/{id}/{email}', [AdminController::class, 'closeMyAccount']);
Route::get('/user/entries', [UserController::class, 'entryList'])->middleware(['auth', 'verified'])->name('user.entries');
Route::get('/users/entry/edit/{id}', [UserController::class, 'editEntry'])->middleware(['auth', 'verified']);
Route::patch('/user/entry/update', [UserController::class, 'updateEntry'])->middleware(['auth', 'verified']);
Route::get('/stripe/usercheckout', [StripePaymentController::class, 'stripeUserCheckout']);

Route::get('/user/removeEntry/{id}', [UserController::class, 'removeEntry'])->middleware(['auth', 'verified']); // First stage in rentry withdrawal
Route::get('/user/confirmRemoveEntry', [UserController::class, 'confirmRemoveEntry'])->middleware(['auth', 'verified']); // Second stage
Route::get('/user/withdraw/{id}', [UserController::class, 'userWithdraw'])->middleware(['auth', 'verified']); // Final stage

// ADMIN Routes
Route::get('/admin/user/remove/{id}', [AdminController::class, 'adminRemove'])->middleware(['auth', 'verified'])->name('admin.remove');
Route::get('/admin/editUser/{id}', [AdminController::class, 'editUser'])->middleware(['auth', 'verified'])->name('admin.editUser');
Route::patch('/admin/updateUser', [AdminController::class, 'updateUser'])->middleware(['auth', 'verified'])->name('admin.updateUser');

// MAIL Routes
Route::get('/admin/mails', [AdminController::class, 'mailList'])->middleware(['auth', 'verified'])->name('admin.mails');

Route::get('/mail/add', [ClubmailController::class, 'add'])->middleware(['auth', 'verified'])->name('mail.add');

Route::get('/mail/preview/{id}', [ClubmailController::class, 'preview'])->middleware(['auth', 'verified'])->name('mail.preview');

Route::get('/mail/edit/{id}', [ClubmailController::class, 'edit'])->middleware(['auth', 'verified'])->name('mail.edit');
Route::patch('/mail/update', [ClubmailController::class, 'update'])->middleware(['auth', 'verified'])->name('mail.update');
Route::post('/mail/store', [ClubmailController::class, 'store'])->middleware(['auth', 'verified'])->name('mail.store');
Route::get('/mail/sendTestmail', [ClubmailController::class, 'sendTestMail']);

Route::post('/usermail/store', [ClubmailController::class, 'storeUsermail'])->middleware(['auth', 'verified'])->name('usermail.store');
Route::get('/usermail/address_mail/{id}', [ClubmailController::class, 'addressUsermail'])->middleware(['auth', 'verified']);
Route::get('/usermail/add', [ClubmailController::class, 'composeUserEmail'])->middleware(['auth', 'verified']);             // from button on club/mails page -> form
Route::post('/usermail/update', [ClubmailController::class, 'updateUserEmail'])->middleware(['auth', 'verified']);
Route::get('/usermail/edit/{id}', [ClubmailController::class, 'editUserEmail'])->middleware(['auth', 'verified']);
Route::post('/usermail/storeAddressList', [ClubmailController::class, 'storeAddressList'])->middleware(['auth', 'verified']);
Route::get('/usermail/preview/{id}', [ClubmailController::class, 'previewUsermail'])->middleware(['auth', 'verified'])->name('usermail.preview');
Route::get('/usermail/sendMail/{id}', [ClubmailController::class, 'sendMail'])->middleware(['auth', 'verified'])->name('usermail.sendMail');// Start of send -> /usermail/prepare
Route::post('/usermail/send', [ClubmailController::class, 'send'])->middleware(['auth', 'verified'])->name('usermail.send'); // Send
Route::post('/usermail/prepare', [ClubmailController::class, 'prepare'])->middleware(['auth', 'verified'])->name('usermail.prepare');

// RESULT Routes
Route::get('/results/list', [ResultController::class, 'list'])->name('results.list');
Route::get('/results/display/{id}', [ResultController::class, 'display'])->name('results.display.id');
Route::get('/result/edit/{id}', [ResultController::class, 'edit']);
Route::patch('/results/update', [ResultController::class, 'update']);

// CLUB Routes
Route::get('/clubs/list', [ClubController::class, 'list']);
Route::get('/club/mails', [ClubController::class, 'mailList']); // Lists mails
Route::get('/clublist', [ClubController::class, 'clublist']);
Route::get('/club/detail/{id}', [ClubController::class, 'detail']);
Route::get('/club/profile', [ClubController::class, 'profile'])->middleware(['auth', 'verified']);
Route::get('/club/profile/edit', [ClubController::class, 'editProfile'])->middleware(['auth', 'verified']);
Route::get('/clubs/add', [ClubController::class, 'add'])->middleware(['auth', 'verified']);
Route::get('/club/edit/{id}', [ClubController::class, 'edit'])->middleware(['auth', 'verified']);
Route::post('/club/store', [ClubController::class, 'store'])->middleware(['auth', 'verified'])->name('club.store');
Route::patch('/club/update', [ClubController::class, 'update'])->middleware(['auth', 'verified']);
Route::patch('/club/clubUpdate', [ClubController::class, 'clubUpdate'])->middleware(['auth', 'verified']);

// SERIES Routes
Route::get('/series/list', [SeriesController::class, 'list'])->middleware(['auth', 'verified']);
Route::get('/series/detail/{id}', [SeriesController::class, 'detail']);
Route::get('/series/add', [SeriesController::class, 'add'])->middleware(['auth', 'verified']);
Route::get('/series/edit/{id}', [SeriesController::class, 'edit'])->middleware(['auth', 'verified']);
Route::post('/series/store', [SeriesController::class, 'store'])->middleware(['auth', 'verified']);
Route::patch('/series/update', [SeriesController::class, 'update'])->middleware(['auth', 'verified']);


//ABOUT Routes
Route::get('/about', [AboutController::class, 'about']);


//Import routes
Route::get('/import', [ImportController::class, 'showImportForm'])->name('import.form');
Route::post('/import', [ImportController::class, 'importEntries'])->name('import.process');


// MIDDLEWARE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/preferences.update', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
});
