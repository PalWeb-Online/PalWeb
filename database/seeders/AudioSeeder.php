<?php

namespace Database\Seeders;

use App\Models\Audio;
use App\Models\Speaker;
use Illuminate\Database\Seeder;

class AudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speaker::all()->each(function ($speaker) {
            Audio::factory(10)->create(['speaker_id' => $speaker->id]);
        });
    }
}
