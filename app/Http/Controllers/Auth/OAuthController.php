<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserNotificationSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserAuthResource;
use App\Models\Badge;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirect(Request $request): JsonResponse
    {
        if ($request->user() && $request->user()->discord_id) {
            return response()->json([
                'success' => false,
                'message' => __('oauth.discord.already-linked'),
            ], 409);
        }

        $redirect = Socialite::driver('discord')
            ->scopes(['identify', 'email', 'guilds'])
            ->redirect();

        return response()->json([
            'success' => true,
            'redirect_url' => $redirect->getTargetUrl(),
        ]);
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            session()->flash('notification', [
                'type' => 'warning',
                'message' => __('oauth.discord.link-canceled'),
            ]);

            return to_route('homepage');
        }

        try {
            $discordUser = Socialite::driver('discord')->user();
            $user = $request->user();

            if ($user) {
                $this->updateUser($user, $discordUser);
                $this->checkMembership($user, $discordUser->token);

            } else {
                $user = User::where('discord_id', $discordUser->id)->first();

                if ($user) {
                    Auth::login($user);

                    session()->flash('notification', [
                        'type' => __('signin.message.head'),
                        'message' => __('signin.message', ['user' => $user->name]),
                    ]);

                } else {
                    $user = User::where('email', $discordUser->email)->first();

                    if ($user) {
                        Auth::login($user);
                        session()->flash('notification', [
                            'type' => __('signin.message.head'),
                            'message' => __('signin.message', ['user' => $user->name]),
                        ]);

                        $this->updateUser($user, $discordUser);
                        $this->checkMembership($user, $discordUser->token);

                    } else {
                        session()->flash('notification', [
                            'type' => 'warning',
                            'message' => __('oauth.discord.no-linked-account'),
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to authenticate with Discord: '.$e->getMessage());

            session()->flash('notification', [
                'type' => 'error',
                'message' => __('oauth.discord.auth-failed'),
            ]);
        }

        return to_route('homepage');
    }

    protected function updateUser($user, $discordUser): void
    {
        $user->update([
            'discord_id' => $discordUser->id,
            'discord_token' => $discordUser->token,
            'discord_refresh_token' => $discordUser->refreshToken,
        ]);
    }

    protected function checkMembership($user, $token): void
    {
        try {
            $badge = Badge::where('key', 'joined_discord')->first();

            if (! $badge) {
                return;
            }

            $client = new Client;
            $response = $client->get('https://discord.com/api/users/@me/guilds', [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ],
            ]);

            $guilds = json_decode($response->getBody()->getContents(), true);
            $isMember = collect($guilds)->contains('id', '808771806945214474');

            if (! $isMember || $user->badges()->whereKey($badge->id)->exists()) {
                return;
            }

            $user->badges()->attach($badge);

            UserNotificationSent::dispatch(
                $user->id,
                __('badges.get', ['badge' => $badge->title]),
            );

        } catch (\Throwable $e) {
            Log::warning('Failed to check Discord guild membership: '.$e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e,
            ]);
        }
    }

    public function revoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->password) {
            return response()->json([
                'success' => false,
                'message' => __('oauth.discord.no-password'),
            ], 422);
        }

        $token = $user->discord_token;

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => __('oauth.discord.no-token'),
            ], 422);
        }

        try {
            $client = new Client;
            $response = $client->post('https://discord.com/api/oauth2/token/revoke', [
                'form_params' => [
                    'token' => $token,
                    'client_id' => config('settings.discord_client_id'),
                    'client_secret' => config('settings.discord_client_secret'),
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => __('oauth.discord.connection-failed'),
                ], 502);
            }

            $user->update([
                'discord_id' => null,
                'discord_token' => null,
                'discord_refresh_token' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('oauth.discord.revoke-success'),
                'user' => new UserAuthResource($user->fresh()),
            ]);

        } catch (\Throwable $e) {
            Log::error('Failed to disconnect Discord account: '.$e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => __('oauth.discord.revoke-failed'),
            ], 500);
        }
    }

    protected function createUser($discordUser)
    {
        $baseUsername = $discordUser->name;
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername.$counter;
            $counter++;
        }

        $user = User::create([
            'name' => 'Pal',
            'ar_name' => 'رفيق',
            'email' => $discordUser->email,
            'username' => $discordUser->name,
            'password' => null,
            'dialect_id' => '1',
            'discord_id' => $discordUser->id,
            'discord_token' => $discordUser->token,
            'discord_refresh_token' => $discordUser->refreshToken,
        ]);

        event(new Registered($user));

        return $user;
    }
}
