<?php

namespace Tests\Unit\Services;

use App\Models\Term;
use App\Services\TermRelativeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TermRelativeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_duplicate_relative_pairs_independently(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $glossIds = $term->glosses()->pluck('id')->values();

        app(TermRelativeService::class)->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'synonym',
                'gloss_id' => $glossIds[0],
            ],
            [
                'slug' => $relative->slug,
                'type' => 'synonym',
                'gloss_id' => $glossIds[1],
            ],
        ]);

        $outboundRows = DB::table('term_relative')
            ->where('term_id', $term->id)
            ->where('relative_id', $relative->id)
            ->orderBy('id')
            ->get();

        $this->assertCount(2, $outboundRows);
        $this->assertSame(4, DB::table('term_relative')->count());

        foreach ($outboundRows as $row) {
            $reciprocal = DB::table('term_relative')->where('id', $row->reciprocal_id)->first();

            $this->assertNotNull($reciprocal);
            $this->assertSame($relative->id, $reciprocal->term_id);
            $this->assertSame($term->id, $reciprocal->relative_id);
            $this->assertSame($row->id, $reciprocal->reciprocal_id);
        }
    }

    public function test_it_deletes_the_linked_reciprocal_pair(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $service = app(TermRelativeService::class);

        $service->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'variant',
            ],
        ]);

        $this->assertSame(2, DB::table('term_relative')->count());

        $term->unsetRelation('relatives');
        $service->sync($term, []);

        $this->assertSame(0, DB::table('term_relative')->count());
    }

    public function test_component_and_descendant_pairs_must_be_deleted_and_recreated_to_change_type(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $service = app(TermRelativeService::class);

        $service->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'component',
            ],
        ]);

        $pivot = DB::table('term_relative')
            ->where('term_id', $term->id)
            ->where('relative_id', $relative->id)
            ->first();

        $this->expectException(ValidationException::class);

        $term->unsetRelation('relatives');
        $service->sync($term, [
            [
                'pivot_id' => $pivot->id,
                'slug' => $relative->slug,
                'type' => 'descendant',
            ],
        ]);
    }

    public function test_source_relationships_create_null_type_reciprocals(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        app(TermRelativeService::class)->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'source',
            ],
        ]);

        $pivot = DB::table('term_relative')
            ->where('term_id', $term->id)
            ->where('relative_id', $relative->id)
            ->first();
        $reciprocal = DB::table('term_relative')->where('id', $pivot->reciprocal_id)->first();

        $this->assertSame('source', $pivot->type);
        $this->assertNull($reciprocal->type);
    }

    public function test_valence_type_changes_do_not_change_the_null_reciprocal_type(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $glossId = $term->glosses()->value('id');
        $service = app(TermRelativeService::class);

        $service->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'isPatient',
                'gloss_id' => $glossId,
            ],
        ]);

        $pivot = DB::table('term_relative')
            ->where('term_id', $term->id)
            ->where('relative_id', $relative->id)
            ->first();

        $term->unsetRelation('relatives');
        $service->sync($term, [
            [
                'pivot_id' => $pivot->id,
                'slug' => $relative->slug,
                'type' => 'noPatient',
                'gloss_id' => $glossId,
            ],
        ]);

        $pivot = DB::table('term_relative')->where('id', $pivot->id)->first();
        $reciprocal = DB::table('term_relative')->where('id', $pivot->reciprocal_id)->first();

        $this->assertSame('noPatient', $pivot->type);
        $this->assertNull($reciprocal->type);
    }

    public function test_derivative_types_can_change_without_changing_the_source_reciprocal_type(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $service = app(TermRelativeService::class);

        $service->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'ap',
            ],
        ]);

        $pivot = DB::table('term_relative')
            ->where('term_id', $term->id)
            ->where('relative_id', $relative->id)
            ->first();

        $term->unsetRelation('relatives');
        $service->sync($term, [
            [
                'pivot_id' => $pivot->id,
                'slug' => $relative->slug,
                'type' => 'pp',
            ],
        ]);

        $pivot = DB::table('term_relative')->where('id', $pivot->id)->first();
        $reciprocal = DB::table('term_relative')->where('id', $pivot->reciprocal_id)->first();

        $this->assertSame('pp', $pivot->type);
        $this->assertSame('source', $reciprocal->type);
    }

    public function test_source_pairs_must_be_deleted_and_recreated_to_change_type(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $service = app(TermRelativeService::class);

        $service->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'source',
            ],
        ]);

        $pivot = DB::table('term_relative')
            ->where('term_id', $term->id)
            ->where('relative_id', $relative->id)
            ->first();

        $this->expectException(ValidationException::class);

        $term->unsetRelation('relatives');
        $service->sync($term, [
            [
                'pivot_id' => $pivot->id,
                'slug' => $relative->slug,
                'type' => 'ap',
            ],
        ]);
    }

    public function test_a_gloss_cannot_have_the_same_term_with_the_same_relative_type_twice(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $glossId = $term->glosses()->value('id');

        $this->expectException(ValidationException::class);

        app(TermRelativeService::class)->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'synonym',
                'gloss_id' => $glossId,
            ],
            [
                'slug' => $relative->slug,
                'type' => 'synonym',
                'gloss_id' => $glossId,
            ],
        ]);
    }

    public function test_a_non_gloss_relative_cannot_have_the_same_term_with_the_same_type_twice(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        $this->expectException(ValidationException::class);

        app(TermRelativeService::class)->sync($term, [
            [
                'slug' => $relative->slug,
                'type' => 'component',
            ],
            [
                'slug' => $relative->slug,
                'type' => 'component',
            ],
        ]);
    }

    public function test_creating_a_pair_reuses_an_equivalent_unlinked_reciprocal_row(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        $orphanId = DB::table('term_relative')->insertGetId([
            'term_id' => $term->id,
            'relative_id' => $relative->id,
            'type' => 'descendant',
            'gloss_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        app(TermRelativeService::class)->sync($relative, [
            [
                'slug' => $term->slug,
                'type' => 'component',
            ],
        ]);

        $newPivot = DB::table('term_relative')
            ->where('term_id', $relative->id)
            ->where('relative_id', $term->id)
            ->first();
        $orphan = DB::table('term_relative')->where('id', $orphanId)->first();

        $this->assertSame(2, DB::table('term_relative')->count());
        $this->assertSame($orphanId, $newPivot->reciprocal_id);
        $this->assertSame($newPivot->id, $orphan->reciprocal_id);
    }
}
