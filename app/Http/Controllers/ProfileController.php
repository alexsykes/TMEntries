<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePreferences(Request $request): RedirectResponse {
//        dd($request->all());
        $user = $request->user();
        $currentPreferences = $user->preferences;

        if($request->receive_results) {
            $currentPreferences = $currentPreferences | 0b00000001;
        } else {
            $currentPreferences = $currentPreferences & 0b11111110;
        }
        if($request->receive_trials) {
            $currentPreferences = $currentPreferences | 0b00000010;
        } else {
            $currentPreferences = $currentPreferences & 0b11111101;
        }
        if($request->receive_news) {
            $currentPreferences = $currentPreferences | 0b00000100;
        } else {
            $currentPreferences = $currentPreferences & 0b11111011;
        }

        $user->preferences = $currentPreferences;
        $user->save();

        return Redirect::to('/');

    }
}
