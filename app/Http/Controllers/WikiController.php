<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Support\Facades\View;

class WikiController
{
    public function index(): \Illuminate\View\View
    {
        View::share('pageTitle', 'Site Documentation & Descriptive Grammar of Palestinian Arabic');
        View::share('pageDescription',
            'Dive into the most detailed publicly-accessible descriptive grammar of Palestinian Arabic ever; practical enough for learners, rigorous enough for linguists. Everything you need to understand the intricacies of the language is right here.');

        return view('wiki.show', [
            'page' => 'about',
            'terms' => Term::all(),
            'bodyBackground' => 'purple-pastel',
        ]);
    }

    public function show($page): \Illuminate\View\View
    {
        View::share('pageTitle', 'Wiki: '.ucwords($page));
        View::share('pageDescription',
            'Dive into the most detailed publicly-accessible descriptive grammar of Palestinian Arabic ever; practical enough for learners, rigorous enough for linguists. Everything you need to understand the intricacies of the language is right here.');

        return view('wiki.show', [
            'page' => $page,
            'terms' => Term::all(),
            'bodyBackground' => 'purple-pastel',
        ]);
    }
}
