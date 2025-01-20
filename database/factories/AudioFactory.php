<?php

namespace Database\Factories;

use App\Models\Pronunciation;
use App\Models\Speaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AudioFactory extends Factory
{
    protected static array $usedPairs = [];

    public function definition(): array
    {
        return [
            'filename' => Str::uuid(),
            'pronunciation_id' => function (array $attributes) {
                $dialect = Speaker::find($attributes['speaker_id'])->dialect;
                $dialectIds = $dialect->ancestors->sortDesc()->pluck('id')->prepend($dialect->id);

                do {
                    $pronunciation = Pronunciation::whereIn('dialect_id', $dialectIds)
                        ->whereDoesntHave('audios', function ($query) use ($attributes) {
                            $query->where('speaker_id', $attributes['speaker_id']);
                        })
                        ->inRandomOrder()
                        ->first();

                } while ($pronunciation && in_array([$attributes['speaker_id'], $pronunciation->id], self::$usedPairs));

                if ($pronunciation) {
                    self::$usedPairs[] = [$attributes['speaker_id'], $pronunciation->id];
                }

                return $pronunciation?->id;
            },
        ];
    }
}
