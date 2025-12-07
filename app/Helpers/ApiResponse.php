<?php

namespace App\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;
// 
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{

    /**
     * Build standard response_info block.
     */
    protected static function responseInfo(array $overrides = []): array
    {
        $base = [
            'api_version' => $overrides['api_version'] ?? 'v1',
            'timestamp'   => $overrides['timestamp'] ?? now()->toISOString(),
        ];

        return array_merge($base, $overrides);
    }

    /**
     * Build seo block.
     */
    protected static function seo(?string $canonical = null, array $overrides = []): array
    {
        $url = $canonical ?? url()->current();

        $base = [
            'canonical' => $url,
            'lang'      => $overrides['lang'] ?? 'en',
            'robots'    => $overrides['robots'] ?? 'index, follow',
        ];

        return array_merge($base, $overrides);
    }

    /**
     * Build pagination meta from a Paginator instance.
     */
    // protected static function paginationMeta(AbstractPaginator $paginator): array
    // {
    //     // Use paginator methods to be safe across types
    //     $current = $paginator->currentPage();
    //     $perPage = $paginator->perPage();
    //     $total   = $paginator->total();
    //     $last    = $paginator->lastPage();
    //     $from    = $paginator->firstItem();
    //     $to      = $paginator->lastItem();
    //     $count   = $paginator->count();

    //     return [
    //         'current_page' => $current,
    //         'last_page'    => $last,
    //         'per_page'     => $perPage,
    //         'total'        => $total,
    //         'from'         => $from,
    //         'to'           => $to,
    //         'count'        => $count,
    //     ];
    // }

    protected static function paginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'from'         => $paginator->firstItem(),
            'to'           => $paginator->lastItem(),
            'count'        => $paginator->count(),

        ];
    }

    /**
     * Build links block for paginator.
     */
    protected static function paginationLinks(AbstractPaginator $paginator, ?string $self = null): array
    {
        $arr = $paginator->toArray();

        return [
            'self'  => $self ?? url()->current(),
            'first' => $arr['first_page_url'] ?? null,
            'last'  => $arr['last_page_url'] ?? null,
            'prev'  => $arr['prev_page_url'] ?? null,
            'next'  => $arr['next_page_url'] ?? null,
        ];
    }

    /**
     * Generic success with custom payload.
     */
    public static function success(string $message = 'Success', $data = null, int $code = 200): JsonResponse
    {
        $payload = [
            'status'  => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
            'meta'    => [
                'response_info' => self::responseInfo(),
            ]
        ];

        return response()->json($payload, $code);
    }

    /**
     * Standard error response.
     */
    public static function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $payload = [
            'status'  => false,
            'code'    => $code,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            // Convention: include 'errors' key for validation/details
            $payload['errors'] = $errors;
        }

        $payload['meta'] = [
            'response_info' => self::responseInfo(),
        ];

        return response()->json($payload, $code);
    }

    /**
     * Shortcut for 401 Unauthorized.
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Shortcut for 403 Forbidden.
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Shortcut for 404 Not Found.
     */
    public static function notFound(string $message = 'Not Found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Shortcut for 500 Server Error.
     */
    public static function serverError(string $message = 'Server Error', $errors = null): JsonResponse
    {
        return self::error($message, 500, $errors);
    }

    /**
     * Validation Error (422)
     */
    // public static function validationError($errors, $message = 'Validation failed', $code = 422)
    // {
    //     return self::error($message, $code, $errors);
    // }


    /**
     * Build response for a single item (resource raw data).
     *
     * @param  array|object|null  $resourceData  Raw data (typically Resource->toArray())
     * @param  string             $message
     * @param  int                $code
     * @param  array              $options  optional keys: 'response_info', 'seo', 'extra_meta'
     */
    public static function item($resourceData = null, string $message = 'Resource retrieved successfully', int $code = 200, array $options = []): JsonResponse
    {
        $meta = $options['extra_meta'] ?? [];
        $selfUrl = $options['self'] ?? url()->current();

        $payload = [
            'status'  => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $resourceData,
            'meta'    => array_merge(
                [
                    'response_info' => self::responseInfo($options['response_info'] ?? []),
                ],
                $options['extra_meta'] ?? [],
            ),
            'seo' => self::seo($options['seo']['canonical'] ?? $selfUrl, $options['seo'] ?? []),

        ];

        return response()->json($payload, $code);
    }


    /**
     * Build response for a paginated collection.
     *
     * @param  \Illuminate\Pagination\AbstractPaginator  $paginator  paginator instance
     * @param  array|mixed                               $itemsData  array returned from Resource collection (already transformed)
     * @param  string                                    $message
     * @param  int                                       $code
     * @param  array                                     $options    optional: 'response_info', 'seo', 'self', 'extra_meta'
     */
    public static function collection(AbstractPaginator $paginator, $itemsData = [], string $message = 'Resources retrieved successfully', int $code = 200, array $options = []): JsonResponse
    {
        $selfUrl = $options['self'] ?? url()->current();

        $payload = [
            'status'  => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $itemsData,
            'meta'    => array_merge(
                [
                    'pagination'    => self::paginationMeta($paginator),
                    'links'   => self::paginationLinks($paginator, $selfUrl),
                    'response_info' => self::responseInfo($options['response_info'] ?? []),
                ],
                // $options['extra_meta'] ?? [],

            ),
            'seo'   => self::seo($options['seo']['canonical'] ?? $selfUrl, $options['seo'] ?? []),

        ];

        return response()->json($payload, $code);
    }
}


// ----------------------------------------------------
// ------------------- Note ---------------------------
// ----------------------------------------------------
//meta = معلومات عن الـ response نفسه.
// seo = معلومات يستخدمها الـ front-end لتحسين العرض والـ SEO.
// composer dump-autoload
// ----------------------------------------------------