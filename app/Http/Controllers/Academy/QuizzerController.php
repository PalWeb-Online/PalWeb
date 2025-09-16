<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckResource;
use App\Http\Resources\DialogResource;
use App\Models\Deck;
use App\Models\Dialog;
use App\Services\QuizService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizzerController extends Controller
{
    public function __construct(private readonly QuizService $quizService)
    {
    }

    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Academy/Quizzer/Index', [
            'section' => 'academy',
            'decks' => DeckResource::collection(Deck::whereHasBookmark($request->user())->with(['terms', 'scores'])->get()),
        ]);
    }

    public function show(string $scorable_type, int $scorable_id): \Inertia\Response|RedirectResponse
    {
        $model = $this->getModel($scorable_type, $scorable_id);

        $model->load($scorable_type === 'deck' ? ['terms', 'scores'] : ['sentences']);

        $isEmpty = $scorable_type === 'deck' ? $model->terms->isEmpty() : $model->sentences->isEmpty();

        if ($isEmpty) {
            session()->flash('notification',
                ['type' => 'warning', 'message' => __("You can't Quiz an empty :type!", ['type' => ucfirst($scorable_type)])]);

            return to_route('quizzer.index');
        }

        return Inertia::render('Academy/Quizzer/Show', [
            'section' => 'academy',
            'model' => $scorable_type === 'deck' ? new DeckResource($model) : new DialogResource($model),
            'scorable_type' => $scorable_type,
        ]);
    }

    public function generate(Request $request, string $scorable_type, int $scorable_id): JsonResponse
    {
        $model = $this->getModel($scorable_type, $scorable_id);
        $settings = $request->get('settings', []);

        $quiz = $this->quizService->generateQuiz($model, $settings);

        return response()->json(['quiz' => $quiz]);
    }

    private function getModel(string $type, int $id): Model
    {
        $class = match ($type) {
            'deck' => Deck::class,
            'dialog' => Dialog::class,
            default => abort(404),
        };

        return $class::findOrFail($id);
    }
}
