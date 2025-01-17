<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController
{
    public function store(Request $request, $language): RedirectResponse
    {
        $allowedLanguages = collect(['en', 'es', 'ar']);

        if ($allowedLanguages->contains($language)) {
            $user = auth()->user();
            if ($user) {
                $user->language = $language;
                $user->save();
            }

            $request->session()->put('language', $language);
        }

        return back();
    }
}
