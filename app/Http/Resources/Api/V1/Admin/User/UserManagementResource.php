<?php

namespace App\Http\Resources\Api\V1\Admin\User;

use App\Http\Resources\Api\V1\Base\BaseResource;
use Illuminate\Http\Request;

// class UserManagementResource extends JsonResource
class UserManagementResource extends BaseResource
{
    // public function toArray(Request $request): array
    public function toArray($request)
    {
        // return (parent::toArray($request));

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            // 'status'     => $this->status,
            'avatar'     => $this->getAvatarUrl(),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),

            // 'links' => [
            //     'self' => route('api.v1.admin.users.show', $this->id),
            // ]
        ];
    }

    private function getAvatarUrl(): string
    {
        return $this->avatar
            ? url("uploads/users/{$this->avatar}")
            : url("uploads/users/avatar.jpg");
    }

    // public function with(Request $request)
    // {
    //     return [
    //         'links' => [
    //             'self' => route('api.v1.admin.users.show', $this->id),
    //         ]
    //     ];
    // }
}
