<?php

namespace App\Http\Controllers;

use App\Http\Resources\RootResource;
use App\Models\Root;
use App\Services\TermService;
use Inertia\Inertia;

class RootController extends Controller
{
    public function __construct(
        protected TermService $termService
    ) {}

    public function show(Root $root): \Inertia\Response
    {
        $root->load([
            'terms' => fn ($q) => $q
                ->withItemData()
        ]);

        $this->termService->hydratePronunciations($root->terms);

        return Inertia::render('Library/Terms/Root', [
            'section' => 'library',
            'root' => new RootResource($root),
        ]);
    }
}
