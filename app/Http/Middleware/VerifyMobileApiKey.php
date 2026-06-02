<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMobileApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredKey = config('services.mobile.api_key');
        $providedKey = $request->header('X-Mobile-App-Key');

        if (!$configuredKey) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile API key belum dikonfigurasi di server.',
            ], 500);
        }

        if (!$providedKey || !hash_equals($configuredKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile API key tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}
