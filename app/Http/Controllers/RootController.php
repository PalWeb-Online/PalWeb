<?php

namespace App\Http\Controllers;

use App\Http\Resources\RootResource;
use App\Models\Root;
use Inertia\Inertia;

class RootController extends Controller
{
    public function show(Root $root): \Inertia\Response
    {
        return Inertia::render('Library/Terms/Root', [
            'section' => 'library',
            'root' => new RootResource($root->load(['terms.pronunciations'])),
        ]);
    }
}
