<?php

namespace Tests\Feature\Console;

use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LinkReciprocalTermRelativesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_links_duplicate_gloss_synonyms_in_order_and_logs_leftovers_as_missing(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();
        $termGlossIds = $term->glosses()->pluck('id')->values();
        $relativeGlossIds = $relative->glosses()->pluck('id')->values();

        $firstOutboundId = $this->insertRelative($term, $relative, 'synonym', $termGlossIds[0]);
        $secondOutboundId = $this->insertRelative($term, $relative, 'synonym', $termGlossIds[1]);
        $orphanedOutboundId = $this->insertRelative($term, $relative, 'synonym', $term->glosses()->create(['gloss' => 'extra gloss'])->id);
        $firstReverseId = $this->insertRelative($relative, $term, 'synonym', $relativeGlossIds[0]);
        $secondReverseId = $this->insertRelative($relative, $term, 'synonym', $relativeGlossIds[1]);

        $this->artisan('terms:link-reciprocal-relatives')
            ->expectsOutput('Finished linking term relatives. Linked: 2. Missing: 1. Ambiguous: 0. Mismatched: 0. Missing type: 0.')
            ->assertSuccessful();

        $this->assertReciprocalPair($firstOutboundId, $firstReverseId);
        $this->assertReciprocalPair($secondOutboundId, $secondReverseId);
        $this->assertNull(DB::table('term_relative')->where('id', $orphanedOutboundId)->value('reciprocal_id'));
    }

    public function test_it_links_valence_reciprocals_with_different_valence_types(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        $outboundId = $this->insertRelative($term, $relative, 'noPatient', $term->glosses()->value('id'));
        $reverseId = $this->insertRelative($relative, $term, 'hasObject', $relative->glosses()->value('id'));

        $this->artisan('terms:link-reciprocal-relatives')
            ->expectsOutput('Finished linking term relatives. Linked: 1. Missing: 0. Ambiguous: 0. Mismatched: 0. Missing type: 0.')
            ->assertSuccessful();

        $this->assertReciprocalPair($outboundId, $reverseId);
    }

    public function test_valence_reciprocals_do_not_match_same_type_or_null_type_rows(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        $outboundId = $this->insertRelative($term, $relative, 'noPatient', $term->glosses()->value('id'));
        $sameTypeReverseId = $this->insertRelative($relative, $term, 'noPatient', $relative->glosses()->value('id'));
        $nullReverseId = $this->insertRelative($relative, $term, null);

        $this->artisan('terms:link-reciprocal-relatives')
            ->expectsOutput('Unable to link term_relative '.$outboundId.': mismatched_type')
            ->expectsOutput('Unable to link term_relative '.$sameTypeReverseId.': mismatched_type')
            ->expectsOutput('Unable to link term_relative '.$nullReverseId.': missing_type')
            ->expectsOutput('Finished linking term relatives. Linked: 0. Missing: 0. Ambiguous: 0. Mismatched: 2. Missing type: 1.')
            ->assertSuccessful();

        $this->assertNull(DB::table('term_relative')->where('id', $outboundId)->value('reciprocal_id'));
        $this->assertNull(DB::table('term_relative')->where('id', $sameTypeReverseId)->value('reciprocal_id'));
        $this->assertNull(DB::table('term_relative')->where('id', $nullReverseId)->value('reciprocal_id'));
    }

    public function test_it_links_source_rows_to_derivative_reciprocals(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        $sourceId = $this->insertRelative($term, $relative, 'source');
        $derivativeId = $this->insertRelative($relative, $term, 'ap');

        $this->artisan('terms:link-reciprocal-relatives')
            ->expectsOutput('Finished linking term relatives. Linked: 1. Missing: 0. Ambiguous: 0. Mismatched: 0. Missing type: 0.')
            ->assertSuccessful();

        $this->assertReciprocalPair($sourceId, $derivativeId);
    }

    public function test_it_links_derivative_rows_to_source_reciprocals(): void
    {
        $term = Term::factory()->create();
        $relative = Term::factory()->create();

        $derivativeId = $this->insertRelative($term, $relative, 'vn');
        $sourceId = $this->insertRelative($relative, $term, 'source');

        $this->artisan('terms:link-reciprocal-relatives')
            ->expectsOutput('Finished linking term relatives. Linked: 1. Missing: 0. Ambiguous: 0. Mismatched: 0. Missing type: 0.')
            ->assertSuccessful();

        $this->assertReciprocalPair($derivativeId, $sourceId);
    }

    private function insertRelative(Term $term, Term $relative, ?string $type, ?int $glossId = null): int
    {
        $now = now();

        return DB::table('term_relative')->insertGetId([
            'term_id' => $term->id,
            'relative_id' => $relative->id,
            'type' => $type,
            'gloss_id' => $glossId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function assertReciprocalPair(int $firstId, int $secondId): void
    {
        $first = DB::table('term_relative')->where('id', $firstId)->first();
        $second = DB::table('term_relative')->where('id', $secondId)->first();

        $this->assertSame($secondId, $first->reciprocal_id);
        $this->assertSame($firstId, $second->reciprocal_id);
    }
}
