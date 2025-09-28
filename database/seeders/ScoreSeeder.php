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
        $users = User::find([1, 2]);

        foreach ($users as $user) {
            foreach ($users[0]->decks as $deck) {
                if ($deck->terms->isEmpty()) {
                    continue;
                }

                Score::factory(10)->create([
                    'user_id' => $user->id,
                    'scorable_id' => $deck->id,
                ]);
            }
        }
    }
}
