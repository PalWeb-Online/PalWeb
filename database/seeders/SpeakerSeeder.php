<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\User;
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
        $users = User::doesntHave('speaker')->get();
        $users->take(25)->each(function ($user) {
            $speaker = Speaker::factory()->for($user)->create();
            $user->update(['speaker_id' => $speaker->id]);
        });
    }
}
