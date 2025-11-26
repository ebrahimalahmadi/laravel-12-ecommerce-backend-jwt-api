<?php

namespace App\Http\Controllers\Api\V1\Admin\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\User\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Admin\User\UserManagementCollection;
use App\Http\Resources\Api\V1\Admin\User\UserManagementResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // List all users with pagination
    public function index()
    {
        $users = User::latest()->paginate(10);

        return ApiResponse::success(
            'Users retrieved successfully',
            new UserManagementCollection($users)
        );
    }

    // Show single user
    public function show($id)
    {
        try {
            $user = User::find($id);
            // $user = User::findOrFail($id);
            if (!$user) return ApiResponse::notFound('User not found!');

            return ApiResponse::success(
                'User retrieved successfully',
                ['user' => new UserManagementResource($user)]
            );
        } catch (\Exception $th) {
            return ApiResponse::serverError($th->getMessage());
        }
    }

    // Update existing user
    public function update(UpdateProfileRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $data = $request->validated();


        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إذا لم تكن افتراضية
            if ($user->avatar && $user->avatar !== 'avatar.jpg') {
                $oldPath = public_path('uploads/users/' . $user->avatar);
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $file = $request->file('avatar');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $fileName);
            $data['avatar'] = $fileName;
        }

        $user->update($data);

        return ApiResponse::success('User updated successfully', [
            'user' => new UserManagementResource($user)
        ]);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        if ($user->avatar && $user->avatar !== 'avatar.jpg') {
            $oldPath = public_path('uploads/users/' . $user->avatar);
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $user->delete();

        return ApiResponse::success('User deleted successfully', [
            'user' => null
        ]);
    }
}
