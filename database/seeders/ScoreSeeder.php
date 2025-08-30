<?php

namespace Database\Seeders;

use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);

        foreach ($user->decks as $deck) {
            Score::factory(10)->create([
                'user_id' => $user->id,
                'scorable_id' => $deck->id,
                'scorable_type' => get_class($deck),
            ]);
        }
    }
}
