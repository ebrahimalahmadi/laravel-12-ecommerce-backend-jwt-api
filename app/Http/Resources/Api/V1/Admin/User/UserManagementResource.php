<?php

namespace App\Http\Resources\Api\V1\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserManagementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'status'        => $this->status,
            'avatar'        => $this->avatar
                ? url('uploads/users/' . $this->avatar)
                : url('uploads/users/avatar.jpg'),
            'last_login_at' => $this->last_login_at?->format('Y-m-d H:i'),
            'created_at'    => $this->created_at->format('Y-m-d H:i'),
            'updated_at'    => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
