<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\User\ChangePasswordRequest;
use App\Http\Requests\Api\V1\Auth\User\UpdateProfileRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show_profile()
    {

        $user = auth('user')->user();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'User profile retrieved successfully',
            'data'         => [
                'user' => new UserResource($user),
            ],
        ], 200);
    }



    // this method is used to update the user profile
    // يستخدم هذا الطريقة لتحديث ملف المستخدم
    //   حذف الصورة القديمة بدون تكرار
    // رفع صورة جديدة 
    // تخزين الاسم فقط وليس المسار
    // تحديث قاعدة البيانات فعليًا

    public function update_profile(UpdateProfileRequest $request)
    {
        $user = auth('user')->user();

        // جمع البيانات باستثناء الصورة
        $data = $request->only(['name', 'phone']);

        // معالجة رفع الصورة
        if ($request->hasFile('avatar')) {

            // حذف الصورة القديمة إذا لم تكن الصورة الافتراضية
            if ($user->avatar && $user->avatar !== 'avatar.jpg') {
                $oldPath = public_path('uploads/users/' . $user->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // رفع الصورة الجديدة
            $file      = $request->file('avatar');
            $fileName  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $fileName);

            // تخزين اسم الصورة
            $data['avatar'] = $fileName;
        }

        // تحديث البيانات
        $user->update($data);

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Profile updated successfully',
            'data'    => [
                'user' => new UserResource($user),
            ],
        ], 200);
    }


    public function change_Password(ChangePasswordRequest $request)
    {
        $user = auth('user')->user();

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // منع استخدام نفس كلمة المرور مرة أخرى
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => 'New password cannot be the same as the current password.',
            ], 400);
        }

        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        auth('user')->logout(true);

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'Password changed successfully. Please login again.',
            'data'         => null
        ], 200);
    }



    public function delete_account()
    {
        $user = auth('user')->user();

        $user->delete();

        // تسجيل الخروج وإلغاء التوكن
        auth('user')->logout();

        return response()->json([
            'status'       => true,
            'code'         => 200,
            'message'      => 'Account deleted successfully',
            'data'         => [
                'user' => null,
            ],
        ], 200);
    }
}
