<?php

namespace Database\Factories;

use App\Models\Pronunciation;
use App\Models\Speaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AudioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'filename' => Str::uuid(),
            'pronunciation_id' => function (array $attributes) {
                $dialectId = Speaker::find($attributes['speaker_id'])->dialect_id;

                return Pronunciation::where('dialect_id', $dialectId)
                    ->whereDoesntHave('audios', function ($query) use ($attributes) {
                        $query->where('speaker_id', $attributes['speaker_id']);
                    })
                    ->inRandomOrder()
                    ->first()
                    ?->id;
            },
        ];
    }
}
