<?php

namespace Tests\Feature\Term;

use App\Models\Category;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Maize\Markable\Models\Bookmark;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_logged_in_to_bookmark(): void
    {
        $this->assertEquals(0, DB::table('markable_bookmarks')->count());
        $term = Term::factory()->for(Category::factory())->create();

        $result = $this->post(route('bookmark', ['term' => $term->slug]));
        $result->assertRedirectToRoute('denied');

        $this->assertEquals(0, DB::table('markable_bookmarks')->count());
    }

    public function test_creates_bookmark_if_does_not_already_exist(): void
    {
        $this->assertEquals(0, DB::table('markable_bookmarks')->count());
        $term = Term::factory()->for(Category::factory())->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $result = $this->post(route('bookmark', ['term' => $term->slug]));
        $result->assertStatus(302);

        $this->assertEquals(1, DB::table('markable_bookmarks')->count());
    }

    public function test_removes_bookmark_if_exists(): void
    {
        $this->assertEquals(0, DB::table('markable_bookmarks')->count());
        $term = Term::factory()->for(Category::factory())->create();
        $user = User::factory()->create();
        Bookmark::toggle($term, $user);
        $this->assertEquals(1, DB::table('markable_bookmarks')->count());

        Sanctum::actingAs($user);
        $result = $this->post(route('bookmark', ['term' => $term->slug]));
        $result->assertStatus(302);

        $this->assertEquals(0, DB::table('markable_bookmarks')->count());
    }
}
