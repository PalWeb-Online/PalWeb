<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class DiscordController extends Controller
{
    public function joined(Request $request): void
    {
        $badge = Badge::where('name', 'No FOMO')->first();
        $user = User::where('discord_id', $request->input('discordId'))->first();

        if ($user && ! $user->badges->contains($badge->id)) {
            $user->badges()->attach($badge);
        }
    }
}
