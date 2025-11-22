<?php

namespace App\Http\Controllers\Api\V1\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\Admin\ChangePasswordRequest;
use App\Http\Requests\Api\V1\Auth\Admin\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Admin\AdminResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    // عرض بيانات البروفايل
    public function show_profile()
    {
        $admin = auth('admin')->user();

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Admin profile retrieved successfully',
            'data'   => [
                'admin' => new AdminResource($admin),
            ],
        ], 200);
    }


    public function update_profile(UpdateProfileRequest $request)
    {
        $admin = auth('admin')->user();

        $data = $request->only(['name', 'phone']);

        if ($request->hasFile('avatar')) {

            // حذف الصورة القديمة إذا لم تكن الصورة الافتراضية
            if ($admin->avatar && $admin->avatar !== 'avatar.jpg') {
                $oldPath = public_path('uploads/admins/' . $admin->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // رفع الصورة الجديدة
            $file      = $request->file('avatar');
            $fileName  = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/admins'), $fileName);

            // تخزين اسم الصورة
            $data['avatar'] = $fileName;
        }

        $admin->update($data);

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Profile updated successfully',
            'data'    => [
                'admin' => new AdminResource($admin),
            ],
        ], 200);
    }


    public function change_password(ChangePasswordRequest $request)
    {
        $admin = auth('admin')->user();

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // منع استخدام نفس كلمة المرور
        if (Hash::check($request->new_password, $admin->password)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => 'New password cannot be the same as the current password.',
            ], 400);
        }

        // تحديث كلمة المرور
        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        auth('admin')->logout(true); // تسجيل الخروج بعد تغيير كلمة المرور

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Password changed successfully. Please login again.',
            'data'    => null,
        ], 200);
    }

    // حذف الحساب
    public function delete_account()
    {
        $admin = auth('admin')->user();
        $admin->delete();

        auth('admin')->logout(); // تسجيل الخروج وإلغاء التوكن

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Account deleted successfully',
            'data'    => [
                'admin' => null,
            ],
        ], 200);
    }
}
