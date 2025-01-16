<?php

namespace App\Http\Controllers;

use App\Models\MissingTerm;
use Flasher\Prime\FlasherInterface;

class MissingTermController extends Controller
{
    public function __construct(
        protected FlasherInterface $flasher,
    ) {}

    public function destroy(
        MissingTerm $missingTerm
    ) {
        $missingTerm->delete();

        $this->flasher->addSuccess(__('deleted', ['thing' => $missingTerm->translit]));

        return to_route('terms.todo');
    }
}
