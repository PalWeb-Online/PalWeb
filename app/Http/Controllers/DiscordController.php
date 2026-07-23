<?php

namespace App\Http\Controllers;

use App\Events\UserNotificationSent;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class DiscordController extends Controller
{
    public function joined(Request $request): void
    {
        $badge = Badge::where('key', 'joined_discord')->first();
        $user = User::where('discord_id', $request->input('discordId'))->first();

        if ($user && $badge && ! $user->badges()->whereKey($badge->id)->exists()) {
            $user->badges()->attach($badge);

            UserNotificationSent::dispatch(
                $user->id,
                __('badges.get', ['badge' => $badge->title]),
            );
        }
    }
}
