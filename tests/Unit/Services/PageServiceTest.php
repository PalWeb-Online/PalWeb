<?php

namespace Tests\Unit\Services;

use App\Models\Page;
use App\Services\PageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_inserts_a_new_top_level_page_at_the_requested_position(): void
    {
        $first = $this->createPage('First', 1);
        $second = $this->createPage('Second', 2);
        $third = $this->createPage('Third', 3);

        $inserted = PageService::upsertWithSiblingPosition(null, $this->pageData([
            'title' => 'Inserted',
            'slug' => 'inserted',
            'position' => 2,
        ]));

        $this->assertSame(1, $first->refresh()->position);
        $this->assertSame(2, $inserted->refresh()->position);
        $this->assertSame(3, $second->refresh()->position);
        $this->assertSame(4, $third->refresh()->position);
    }

    public function test_it_moves_an_existing_page_within_its_sibling_group(): void
    {
        $first = $this->createPage('First', 1);
        $second = $this->createPage('Second', 2);
        $third = $this->createPage('Third', 3);

        PageService::upsertWithSiblingPosition($third, $this->pageData([
            'title' => $third->title,
            'slug' => $third->slug,
            'position' => 1,
        ]));

        $this->assertSame(2, $first->refresh()->position);
        $this->assertSame(3, $second->refresh()->position);
        $this->assertSame(1, $third->refresh()->position);
    }

    public function test_it_moves_a_page_between_parent_sibling_groups(): void
    {
        $oldParent = $this->createPage('Old Parent', 1);
        $newParent = $this->createPage('New Parent', 2);

        $oldFirst = $this->createPage('Old First', 1, $oldParent->id);
        $moving = $this->createPage('Moving', 2, $oldParent->id);
        $oldThird = $this->createPage('Old Third', 3, $oldParent->id);

        $newFirst = $this->createPage('New First', 1, $newParent->id);
        $newSecond = $this->createPage('New Second', 2, $newParent->id);

        PageService::upsertWithSiblingPosition($moving, $this->pageData([
            'title' => $moving->title,
            'slug' => $moving->slug,
            'parent_id' => $newParent->id,
            'position' => 2,
        ]));

        $this->assertSame($oldParent->id, $oldFirst->refresh()->parent_id);
        $this->assertSame(1, $oldFirst->position);
        $this->assertSame(2, $oldThird->refresh()->position);

        $this->assertSame($newParent->id, $moving->refresh()->parent_id);
        $this->assertSame(1, $newFirst->refresh()->position);
        $this->assertSame(2, $moving->position);
        $this->assertSame(3, $newSecond->refresh()->position);
    }

    private function createPage(string $title, int $position, ?int $parentId = null): Page
    {
        return Page::factory()->create($this->pageData([
            'title' => $title,
            'slug' => str($title)->slug()->toString(),
            'position' => $position,
            'parent_id' => $parentId,
        ]));
    }

    private function pageData(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'page-'.str()->uuid(),
            'title' => 'Page',
            'summary' => null,
            'document' => [
                'schemaVersion' => 1,
                'blocks' => [],
            ],
            'status' => 'draft',
            'locale' => 'en',
            'published_at' => null,
            'position' => 1,
            'parent_id' => null,
        ], $overrides);
    }
}
