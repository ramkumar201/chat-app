<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\UserInfo;

class ProfileController extends Controller
{

    protected $loginUser;

    public function __construct()
    {
        $this->loginUser = Auth::user();
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
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

        $loginUser = Auth::user();

        // Update the user profile
        $data = (object) $request->all();
        $userData = User::find($loginUser->id);
        $userData->update([
            'name' => $data->name,
            'email' => $data->email,
            'photo' => $data->photo,
        ]);
        UserInfo::updateOrCreate([
            'user_id' => $loginUser->id
        ], [
            'bio' => $data->bio ?? null,
            'address' => $data->address ?? null,
            'city' => $data->city ?? null,
            'state' => $data->state ?? null,
            'country' => $data->country ?? null,
            'phoneno' => $data->phoneno ?? null,
        ]);

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
