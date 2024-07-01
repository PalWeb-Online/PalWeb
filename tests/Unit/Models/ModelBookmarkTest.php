<?php

namespace Tests\Unit\Models;

use App\Exceptions\NullBookmarkException;
use App\Models\Category;
use App\Models\ModelBookmark;
use App\Models\Term;
use App\Models\TermBookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maize\Markable\Models\Bookmark;
use Tests\TestCase;

class ModelBookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_fails_if_you_have_not_specified_a_modelBookmark()
    {
        $this->expectException(NullBookmarkException::class);
        $this->getNoBookmark()::get();
    }

    public function test_can_retrieve_bookmarks()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $cat = Category::factory()->create();
        $terms = Term::factory(5)->create(['category_id' => $cat->id]);

        // Term bookmarks
        Bookmark::add($terms[2], $user1);
        Bookmark::add($terms[3], $user1);
        Bookmark::add($terms[4], $user2);

        // A category bookmark that should get filtered out
        Bookmark::add($cat, $user1);

        $this->assertEquals(2, $this->getTermBookmark()->whereUser($user1)->get()->count());
        $this->assertEquals(1, $this->getTermBookmark()->whereUser($user2)->get()->count());
    }

    /**
     * Returns a term bookmark
     */
    protected function getTermBookmark()
    {
        return new TermBookmark();
    }

    /**
     * Returns a model bookmark that has not set a bookmarkModel so will fail
     */
    protected function getNoBookmark()
    {
        return new class extends ModelBookmark
        {
        };
    }
}
