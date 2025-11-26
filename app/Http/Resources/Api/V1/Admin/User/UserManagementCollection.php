<?php

namespace App\Http\Resources\Api\V1\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserManagementCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'data' => $this->collection,
            'data' => UserManagementResource::collection($this->collection),

            // meta = معلومات كاملة عن حالة الـ Pagination.
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
            // links = روابط التنقّل بين الصفحات.
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }
}



// -------------------------- Notes -----------------------------------//
// Resource Collection vs Resource
// 1. Resource Collection:
//    - Represents a collection of resources (e.g., multiple users).
//    - Used when returning lists of resources, often with pagination.
//    - Extends the ResourceCollection class.
//    - Example: UserManagementCollection.
// 2. Resource:
//    - Represents a single resource (e.g., a single user).
//    - Used when returning detailed information about one resource.
//    - Extends the JsonResource class.
//    - Example: UserManagementResource.
// ---------------------------------------------------------------------//

// // عدد المستخدمين داخل الصفحة الحالية فقط
// 'Users Count' => $this->collection->count(),

// 'meta' => [
//     // رقم الصفحة الحالية المعروضة الآن
//     'current_page' => $this->currentPage(),

//     // رقم آخر صفحة (الحد الأقصى للصفحات)
//     'last_page' => $this->lastPage(),

//     // عدد العناصر التي يتم عرضها في كل صفحة
//     'per_page' => $this->perPage(),

//     // إجمالي عدد العناصر الموجودة في قاعدة البيانات
//     'total' => $this->total(),

//     // رقم أول عنصر في الصفحة الحالية (مثال: 21)
//     'from' => $this->firstItem(),

//     // رقم آخر عنصر في الصفحة الحالية (مثال: 30)
//     'to' => $this->lastItem(),
// ],

// 'links' => [
    //     // رابط أول صفحة في النتائج
    //     'first' => $this->url(1),

    //     // رابط آخر صفحة (الصفحة النهائية)
    //     'last' => $this->url($this->lastPage()),
    
    //     // رابط الصفحة السابقة (null إذا كنت في الصفحة الأولى)
    //     'prev' => $this->previousPageUrl(),
    
    //     // رابط الصفحة التالية (null إذا كنت في آخر صفحة)
    //     'next' => $this->nextPageUrl(),
    // ],
    
    // ---------------------------------------------------------------------//