<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\Admin\ChangePasswordRequest;
use App\Http\Requests\Api\V1\Auth\Admin\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Admin\AdminResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    /**
     *  Get Admin Profile
     */
    public function show_profile()
    {
        try {
            $admin = auth('admin')->user();

            if (!$admin) {
                return ApiResponse::unauthorized('Unauthorized access');
            }

            return ApiResponse::success(
                'Admin profile retrieved successfully',
                ['admin' => new AdminResource($admin)]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  Update Profile
     */
    public function update_profile(UpdateProfileRequest $request)
    {
        try {
            $admin = auth('admin')->user();

            if (!$admin) {
                return ApiResponse::unauthorized();
            }

            $data = $request->only(['name', 'phone']);

            /**  Handle Avatar Upload */
            if ($request->hasFile('avatar')) {

                // delete old avatar
                if ($admin->avatar && $admin->avatar !== 'avatar.jpg') {
                    $oldPath = public_path('uploads/admins/' . $admin->avatar);
                    if (file_exists($oldPath)) unlink($oldPath);
                    //if (file_exists($oldPath)) {
                    //unlink($oldPath);}
                }

                // upload new image
                $file      = $request->file('avatar');
                $fileName  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/admins'), $fileName);

                $data['avatar'] = $fileName;
            }

            $admin->update($data);

            return ApiResponse::success(
                'Profile updated successfully',
                ['admin' => new AdminResource($admin)]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


    /**
     *  Change Password
     */
    public function change_password(ChangePasswordRequest $request)
    {
        try {
            $admin = auth('admin')->user();

            if (!$admin) {
                return ApiResponse::unauthorized();
            }

            /**  Verify old password */
            if (!Hash::check($request->current_password, $admin->password)) {
                return ApiResponse::error('Current password is incorrect', 400);
            }

            /**  Prevent using same password */
            if (Hash::check($request->new_password, $admin->password)) {
                return ApiResponse::error('New password cannot be the same as the current password', 400);
            }

            $admin->update([
                'password' => Hash::make($request->new_password)
            ]);

            auth('admin')->logout(true);

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
            $admin = auth('admin')->user();

            if (!$admin) {
                return ApiResponse::unauthorized();
            }

            $admin->delete();
            auth('admin')->logout();

            return ApiResponse::success(
                'Account deleted successfully',
                ['admin' => null]
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }
}
