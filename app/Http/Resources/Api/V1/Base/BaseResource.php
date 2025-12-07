<?php

namespace App\Http\Resources\Api\V1\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
