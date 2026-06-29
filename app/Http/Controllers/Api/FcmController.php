<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FcmController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        FcmToken::updateOrCreate(
            ['token' => $request->token],
            [
                'user_id'      => auth()->id(),
                'last_used_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
