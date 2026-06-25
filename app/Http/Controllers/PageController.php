<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertPageRequest;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class PageController extends Controller
{
    public function show(Page $page): Response
    {
        Gate::authorize('view', $page);

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
                    'status' => $page->status,
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

    public function store(UpsertPageRequest $request): JsonResponse|RedirectResponse
    {
        $page = PageService::upsertWithSiblingPosition(null, $request->validated());

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

    public function update(UpsertPageRequest $request, Page $page): JsonResponse|RedirectResponse
    {
        $page = PageService::upsertWithSiblingPosition($page, $request->validated());

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

    public function destroy(Page $page): JsonResponse
    {
        try {
            Gate::authorize('delete', $page);

            DB::transaction(function () use ($page) {
                $page->delete();
            });

            return response()->json([
                'success' => true,
                'message' => __('deleted', ['thing' => 'Page']),
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to delete Page.', [
                'page_id' => $page->id,
                'exception' => $e,
            ]);

            return $this->failureJsonResponse('Unable to delete Page.', $e);
        }
    }
}
