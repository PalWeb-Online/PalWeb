<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPrivacyController
{
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->private = !$user->private;
        $user->private ? $status = 'Private' : $status = 'Public';
        $user->save();

        return response()->json([
            'isPrivate' => $user->private,
            'message' => __('privacy.updated', ['status' => $status]),
        ]);
    }
}
