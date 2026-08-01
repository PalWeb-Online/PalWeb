<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController
{
    public function store(Request $request, $language): RedirectResponse
    {
        $allowedLanguages = collect(['en', 'es', 'ar']);

        if ($allowedLanguages->contains($language)) {
            $user = $request->user();
            if ($user) {
                $user->language = $language;
                $user->save();
            }

            Cookie::queue(Cookie::forever('language', $language));
        }

        return back();
    }
}
