<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class SystemController extends Controller
{
    public function migrate(Request $request): JsonResponse
    {
        // if (! config('app.migrate_enabled')) {
        //     return response()->json(['error' => 'Not found'], 404);
        // }

        // $token = (string) $request->query('token');
        // $expected = (string) config('app.migrate_token');

        // if ($expected === '' || ! hash_equals($expected, $token)) {
        //     return response()->json(['error' => 'Forbidden'], 403);
        // }

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Migration failed',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'output' => Artisan::output(),
        ]);
    }

    public function seed(Request $request): JsonResponse
    {
        // if (! config('app.migrate_enabled')) {
        //     return response()->json(['error' => 'Not found'], 404);
        // }

        // $token = (string) $request->query('token');
        // $expected = (string) config('app.migrate_token');

        // if ($expected === '' || ! hash_equals($expected, $token)) {
        //     return response()->json(['error' => 'Forbidden'], 403);
        // }

        try {
            Artisan::call('db:seed', ['--force' => true]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Seeding failed',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'output' => Artisan::output(),
        ]);
    }
}
