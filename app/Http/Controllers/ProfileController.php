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
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

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
    public function update(Request $request)
    {
        try {
            $loginUser = Auth::user();

            $data = (object) $request->all();
            $userData = User::find($loginUser->id);
            $insertUserData['name'] = $data->name;
            $insertUserData['email'] = $data->email;
            if ($request->hasFile('photo') && $request->file('photo')) {
                $insertUserData['photo'] = $this->uploadImage($request->file('photo'), 'userpic');
            }
            $userData->update($insertUserData);

            $insertInfoData['address'] = $data->address ?? null;
            $insertInfoData['city'] = $data->city ?? null;
            $insertInfoData['state'] = $data->state ?? null;
            $insertInfoData['country'] = $data->country ?? null;
            $insertInfoData['phoneno'] = $data->phoneno ?? null;
            if ($request->hasFile('bio') && $request->file('bio')) {
                $insertInfoData['bio'] = $this->uploadImage($request->file('photo'), 'userbio');
            }
            UserInfo::updateOrCreate([
                'user_id' => $loginUser->id
            ], $insertInfoData);

            return response()->json([
                'status' => 200,
                'message' => 'Profile Update successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong!',
                'data' => $e->getMessage(),
            ]);
        }

    }

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

    private function uploadImage($file, $folder)
    {
        $fileName = $file->getClientOriginalName();

        $storage = app('firebase.storage');
        $bucket = $storage->getBucket();

        $destinationFilePath = $folder."/".$fileName;

        // Upload the file
        $object = $bucket->upload(
            file_get_contents($file),
            ['name' => $destinationFilePath]
        );
        return $object->signedUrl(new \DateTime('9999-12-31T23:59:59Z'));
    }
}
