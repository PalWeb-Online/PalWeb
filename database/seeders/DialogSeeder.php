<?php

namespace Database\Seeders;

use App\Models\Dialog;
use App\Models\Sentence;

class DialogSeeder
{
    public function run(): void
    {
        $dialogs = Dialog::factory(5)->create();

        foreach ($dialogs as $dialog) {
            $sentences = Sentence::inRandomOrder()->take(10)->get();

            foreach ($sentences as $i => $sentence) {
                $sentence->update([
                    'dialog_id' => $dialog->id,
                    'speaker' => fake()->name,
                    'position' => $i + 1,
                ]);
            }
        }
    }
}
