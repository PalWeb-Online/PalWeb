<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertPageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function show(Page $page): Response
    {
        return Inertia::render('Wiki/Show', [
            'section' => 'wiki',
            'pageId' => $page->id,
        ]);
    }

    public function fetch(Page $page): JsonResponse
    {
        $page->load('parent:id,slug,title');

        return response()->json([
//            todo: this is fine, but maybe create PageResource later on
            'page' => $page,
            'descendant_ids' => $this->getDescendantPageIds($page->id),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'page_id' => ['nullable', 'integer', 'exists:pages,id'],
        ]);

        $q = trim($validated['q'] ?? '');
        $pageId = $validated['page_id'] ?? null;
        $excludedIds = $pageId
            ? [$pageId, ...$this->getDescendantPageIds($pageId)]
            : [];

        $pages = Page::query()
            ->select(['id', 'slug', 'title', 'parent_id'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query
                        ->where('title', 'like', $q.'%')
                        ->orWhere('slug', 'like', $q.'%');
                });
            })
            ->when(! empty($excludedIds), function ($query) use ($excludedIds) {
                $query->whereNotIn('id', $excludedIds);
            })
            ->orderBy('title')
            ->limit(10)
            ->get()
            ->map(function (Page $page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'parent_id' => $page->parent_id,
                ];
            })
            ->values();

        return response()->json([
            'data' => $pages,
        ]);
    }

    private function getDescendantPageIds(int $pageId): array
    {
        $descendantIds = [];
        $pendingIds = [$pageId];

        while (! empty($pendingIds)) {
            $childIds = Page::query()
                ->whereIn('parent_id', $pendingIds)
                ->pluck('id')
                ->all();

            $childIds = array_values(array_diff($childIds, $descendantIds));

            if (empty($childIds)) {
                break;
            }

            $descendantIds = array_merge($descendantIds, $childIds);
            $pendingIds = $childIds;
        }

        return $descendantIds;
    }

    public function getWikiTree(): JsonResponse
    {
        $pages = Page::query()
            ->orderBy('position')
            ->orderBy('title')
            ->get(['id', 'slug', 'title', 'status', 'position', 'parent_id']);

        return response()->json([
            'page_tree' => $this->buildWikiTree($pages),
        ]);
    }

    private function buildWikiTree($pages, ?int $parentId = null): array
    {
        return $pages
            ->where('parent_id', $parentId)
            ->map(function (Page $page) use ($pages) {
                return [
                    'id' => $page->id,
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'sort_order' => $page->sort_order,
                    'position' => $page->position,
                    'parent_id' => $page->parent_id,
                    'children' => $this->buildWikiTree($pages, $page->id),
                ];
            })
            ->values()
            ->all();
    }

    public function edit(?Page $page = null): Response
    {
        return Inertia::render('Wiki/Edit', [
            'section' => 'wiki',
            'pageId' => $page?->id,
        ]);
    }

    public function store(UpsertPageRequest $request): JsonResponse | RedirectResponse
    {
        $page = Page::create($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'page' => $page->load('parent:id,slug,title'),
                'message' => 'Page created successfully.',
            ], 201);
        }

        return to_route('wiki.show', $page->slug)
            ->with('notification', [
                'type' => 'success',
                'message' => 'Page created successfully.',
            ]);
    }

    public function update(UpsertPageRequest $request, Page $page): JsonResponse | RedirectResponse
    {
        $page->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'page' => $page->refresh()->load('parent:id,slug,title'),
                'message' => 'Page updated successfully.',
            ]);
        }

        return to_route('wiki.show', $page->slug)
            ->with('notification', [
                'type' => 'success',
                'message' => 'Page updated successfully.',
            ]);
    }

    public function destroy(Page $page): JsonResponse | RedirectResponse
    {
        $page->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Page deleted successfully.',
            ]);
        }

        return to_route('wiki.show')
            ->with('notification', [
                'type' => 'success',
                'message' => 'Page deleted successfully.',
            ]);
    }
}
