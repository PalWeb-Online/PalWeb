<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PageService
{
    public static function upsertWithSiblingPosition(?Page $page, array $data): Page
    {
        return DB::transaction(function () use ($page, $data) {
            $oldParentId = $page?->parent_id;
            $targetParentId = self::normalizeParentId($data['parent_id'] ?? null);
            $targetPosition = self::normalizePosition($data['position'] ?? null);

            $data['parent_id'] = $targetParentId;
            $data['position'] = $targetPosition;

            if ($page) {
                $page->update($data);
            } else {
                $page = Page::create($data);
            }

            $page->refresh();

            if ($oldParentId !== $targetParentId) {
                self::renumberSiblings($oldParentId);
            }

            self::renumberSiblings($targetParentId, $page, $targetPosition);

            return $page->refresh();
        });
    }

    private static function renumberSiblings(?int $parentId, ?Page $page = null, ?int $position = null): void
    {
        $siblings = Page::query()
            ->when(
                $parentId,
                fn ($query) => $query->where('parent_id', $parentId),
                fn ($query) => $query->whereNull('parent_id'),
            )
            ->when($page, fn ($query) => $query->whereKeyNot($page->id))
            ->orderBy('position')
            ->orderBy('title')
            ->get();

        if ($page) {
            $siblings = self::insertPageAtPosition($siblings, $page, $position);
        }

        $siblings->values()->each(function (Page $sibling, int $index) {
            $nextPosition = $index + 1;

            if ((int) $sibling->position !== $nextPosition) {
                $sibling->update(['position' => $nextPosition]);
            }
        });
    }

    private static function insertPageAtPosition(Collection $siblings, Page $page, ?int $position): Collection
    {
        $ordered = $siblings->values();
        $index = min(max(self::normalizePosition($position) - 1, 0), $ordered->count());

        $ordered->splice($index, 0, [$page]);

        return $ordered;
    }

    private static function normalizeParentId(mixed $parentId): ?int
    {
        return $parentId ? (int) $parentId : null;
    }

    private static function normalizePosition(mixed $position): int
    {
        return max(1, (int) ($position ?? 1));
    }
}
