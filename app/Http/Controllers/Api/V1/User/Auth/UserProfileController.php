<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\User\ChangePasswordRequest;
use App\Http\Requests\Api\V1\Auth\User\UpdateProfileRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     *  Show User Profile
     */
    public function show_profile()
    {
        try {
            $user = auth('user')->user();

            if (!$user) {
                return ApiResponse::unauthorized('Unauthorized access');
            }

            return ApiResponse::success(
                'User profile retrieved successfully',
                ['user' => new UserResource($user)]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  Update User Profile
     */
    public function update_profile(UpdateProfileRequest $request)
    {
        try {
            $user = auth('user')->user();

            if (!$user) {
                return ApiResponse::unauthorized();
            }

            $data = $request->only(['name', 'phone']);

            /**  Handle avatar upload */
            if ($request->hasFile('avatar')) {

                // delete old avatar if not default
                if ($user->avatar && $user->avatar !== 'avatar.jpg') {
                    $oldPath = public_path('uploads/users/' . $user->avatar);
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                // upload new avatar
                $file      = $request->file('avatar');
                $fileName  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/users'), $fileName);

                $data['avatar'] = $fileName;
            }

            $user->update($data);

            return ApiResponse::success(
                'Profile updated successfully',
                ['user' => new UserResource($user)]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  Change Password
     */
    public function change_Password(ChangePasswordRequest $request)
    {
        try {
            $user = auth('user')->user();

            if (!$user) {
                return ApiResponse::unauthorized();
            }

            // verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return ApiResponse::error('Current password is incorrect', 400);
            }

            // prevent using same password
            if (Hash::check($request->new_password, $user->password)) {
                return ApiResponse::error('New password cannot be the same as the current password', 400);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            auth('user')->logout(true);

            return ApiResponse::success(
                'Password changed successfully. Please login again.',
                null
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     *  Delete Account
     */
    public function delete_account()
    {
        try {
            $user = auth('user')->user();

            if (!$user) {
                return ApiResponse::unauthorized();
            }

            $user->delete();
            auth('user')->logout();

            return ApiResponse::success(
                'Account deleted successfully',
                ['user' => null]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }
}
