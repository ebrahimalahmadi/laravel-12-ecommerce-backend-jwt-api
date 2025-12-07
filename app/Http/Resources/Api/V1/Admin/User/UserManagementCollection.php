<?php

namespace App\Http\Resources\Api\V1\Admin\User;

use App\Http\Resources\Api\V1\Base\BaseCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserManagementCollection extends BaseCollection
// class UserManagementCollection extends ResourceCollection
{
    public $collects = UserManagementResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
