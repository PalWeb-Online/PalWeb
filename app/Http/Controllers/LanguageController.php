<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * This class allows you to change the current language
 */
class LanguageController
{
    public function change(Request $request, $language)
    {
        $allowedLanguages = collect(['en', 'es', 'ar']);

        if ($allowedLanguages->contains($language)) {
            $user = $request->user();
            if ($user) {
                $user->language = $language;
                $user->save();
            }

            $request->session()->put('language', $language);
        }

        return redirect()->back();
    }
}
