<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController
{
    public function store(Request $request, $locale): JsonResponse
    {
        $allowedLocales = collect(['en', 'es', 'ar']);

        if ($allowedLocales->contains($locale)) {
            $user = $request->user();
            if ($user) {
                $user->language = $locale;
                $user->save();
            }

            Cookie::queue(Cookie::forever('language', $locale));
        }

        return response()->json([
            'success' => true,
            'locale' => $locale
        ]);
    }
}
