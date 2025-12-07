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
    // // List all users with pagination
    // public function index(Request $request)
    // {
    //     try {
    //         // $perPage = $request->get('per_page', 5);
    //         // $users = User::latest()->paginate($perPage);
    //         $users = User::latest()->paginate(3);

    //         // raw transformed array using resource collection
    //         $items = UserManagementResource::collection($users);

    //         return ApiResponse::collection(
    //             paginator: $users,
    //             // itemsData: $items,
    //             itemsData: [
    //                 'users' => $items
    //             ],
    //             message: 'Users retrieved successfully',
    //             code: 200,
    //             options: [
    //                 'self' => url('/api/v1/admin/users'),
    //                 'seo'  => [
    //                     'canonical' => url('/api/v1/admin/users'),
    //                 ]
    //             ]
    //         );
    //     } catch (\Exception $th) {
    //         return ApiResponse::serverError($th->getMessage());
    //     }
    // }

    // List all users with pagination
    public function index(Request $request)
    {
        try {
            $users = User::latest()->paginate(3);

            // raw transformed array using resource collection
            $items = UserManagementResource::collection($users);

            return ApiResponse::collection(
                paginator: $users,
                // itemsData: $items,
                itemsData: [
                    'users' => $items,
                ],
                message: 'Users retrieved successfully',
                code: 200,
                options: [
                    'self' => url('/api/v1/admin/users'),
                    'seo'  => [
                        'canonical' => url('/api/v1/admin/users'),
                    ],
                    // 'extra_meta' => [
                    //     'environment' => 'Development',
                    //     'developer by'   => 'Ebrahim Al-Ahmadi',
                    // ],
                ]
            );
        } catch (\Exception $th) {
            return ApiResponse::serverError($th->getMessage());
        }
    }



    /**
     * Display resource by id.
     */
    // public function show($id, Request $request)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return ApiResponse::notFound('User not found');
    //     }

    //     // $data = (new UserManagementResource($user))->toArray($request);
    //     $data = new UserManagementResource($user);

    //     return ApiResponse::item(
    //         resourceData: $data,
    //         message: 'User retrieved successfully',
    //         code: 200,
    //         options: [
    //             'seo' => [
    //                 'canonical' => url("/api/v1/admin/users/{$id}")
    //             ],
    //             'extra_meta' => [
    //                 'developer'  => 'Ebrahim Al-Ahmdi',
    //                 'environment' => 'production'
    //             ]
    //         ]
    //     );
    // }



    public function show($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return ApiResponse::notFound('User not found');
            }

            return ApiResponse::item(
                new UserManagementResource($user),
                'User retrieved successfully',
                200,
                [
                    'extra_meta' => [
                        'request_id' => uniqid(),
                        'developer'  => 'Ebrahim Al-Ahmadi',
                        // 'environment' => 'production'
                        'environment' => 'development'
                    ]
                ]
            );
        } catch (\Exception $th) {
            return ApiResponse::serverError($th->getMessage());
        }
    }

    // // Update existing user
    // public function update(UpdateProfileRequest $request, $id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return ApiResponse::notFound('User not found');
    //     }

    //     $data = $request->validated();


    //     if ($request->hasFile('avatar')) {
    //         // حذف الصورة القديمة إذا لم تكن افتراضية
    //         if ($user->avatar && $user->avatar !== 'avatar.jpg') {
    //             $oldPath = public_path('uploads/users/' . $user->avatar);
    //             if (file_exists($oldPath)) unlink($oldPath);
    //         }

    //         $file = $request->file('avatar');
    //         $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('uploads/users'), $fileName);
    //         $data['avatar'] = $fileName;
    //     }

    //     $user->update($data);

    //     return ApiResponse::success(
    //         [
    //             'user' => new UserManagementResource($user)
    //         ],
    //         'User updated successfully',
    //     );
    //     // return ApiResponse::success('User updated successfully', [
    //     //     'user' => new UserManagementResource($user)
    //     // ]);
    // }


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

        return ApiResponse::success(
            'User updated successfully',
            [
                'user' => new UserManagementResource($user)
            ],
        );
    }


    // // Update existing user
    // public function update(UpdateProfileRequest $request, $id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return ApiResponse::notFound('User not found');
    //     }

    //     $data = $request->validated();


    //     if ($request->hasFile('avatar')) {
    //         // حذف الصورة القديمة إذا لم تكن افتراضية
    //         if ($user->avatar && $user->avatar !== 'avatar.jpg') {
    //             $oldPath = public_path('uploads/users/' . $user->avatar);
    //             if (file_exists($oldPath)) unlink($oldPath);
    //         }

    //         $file = $request->file('avatar');
    //         $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('uploads/users'), $fileName);
    //         $data['avatar'] = $fileName;
    //     }

    //     $user->update($data);

    //     return ApiResponse::success('User updated successfully', [
    //         'user' => new UserManagementResource($user)
    //     ]);
    // }

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
