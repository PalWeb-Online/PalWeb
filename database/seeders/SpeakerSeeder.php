<?php

namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Seeder;

class SpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speaker::factory(10)->create();
    }
}
