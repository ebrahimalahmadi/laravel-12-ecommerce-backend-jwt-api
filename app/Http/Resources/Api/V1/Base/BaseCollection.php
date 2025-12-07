<?php

namespace App\Http\Resources\Api\V1\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    /**
     * Return raw collection array only.
     */
    public function toArray($request)
    {
        return $this->collection->toArray();
    }
}
