<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    public function redirect(Request $request): RedirectResponse
    {
        if ($request->user() && $request->user()->discord_id) {
            $this->flasher->addInfo('Your Discord account is already connected.');

            return back();

        } else {
            return Socialite::driver('discord')->scopes(['identify', 'email', 'guilds'])->redirect();
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $error = request('error') ?? null;
        $errorDescription = request('error_description') ?? null;

        if ($error) {
            Log::error("Discord OAuth error: $error - $errorDescription");
            $this->flasher->addError('You canceled the Discord authentication process.');

            return to_route('homepage');
        }

        try {
            $discordUser = Socialite::driver('discord')->user();
            $user = $request->user();

            if ($user) {
                $this->updateUser($request->user(), $discordUser);
                $this->checkMembership($request->user(), $discordUser->token);

            } else {
                $user = User::where('discord_id', $discordUser->id)->first();

                if ($user) {
                    Auth::login($user);
                    $this->flasher->addFlash('info', __('signin.message', ['user' => $user->name]),
                        __('signin.message.head'));

                } else {
                    $user = User::where('email', $discordUser->email)->first();

                    if ($user) {
                        Auth::login($user);
                        $this->flasher->addFlash('info', __('signin.message', ['user' => $user->name]),
                            __('signin.message.head'));

                        $this->updateUser($user, $discordUser);
                        $this->checkMembership($request->user(), $discordUser->token);

                    } else {
                        $this->flasher->addWarning('We couldn\'t sign you in, because there is no PalWeb account that is connected to or has the same email as the provided Discord account. Please sign in normally first & connect your Discord account before trying to log in this way.');
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to authenticate with Discord: '.$e->getMessage());

            $this->flasher->addError('Failed to authenticate with Discord.');
        }

        return to_route('homepage');
    }

    protected function updateUser($user, $discordUser)
    {
        $user->update([
            'discord_id' => $discordUser->id,
            'discord_token' => $discordUser->token,
            'discord_refresh_token' => $discordUser->refreshToken,
        ]);

        $this->flasher->addSuccess('Connected your account to Discord!');
    }

    protected function checkMembership($user, $token)
    {
        $badge = Badge::where('name', 'No FOMO')->first();

        $client = new Client;
        $response = $client->get('https://discord.com/api/users/@me/guilds', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        $guilds = json_decode($response->getBody()->getContents(), true);
        $isMember = collect($guilds)->contains('id', '808771806945214474');

        if ($isMember && ! $user->badges->contains($badge->id)) {
            $user->badges()->attach($badge);
            $this->flasher->addInfo(__('badges.get', ['badge' => $badge->name]));
        }
    }

    public function revoke(Request $request): RedirectResponse
    {
        if (! $request->user()->password) {
            session()->flash('notification', ['type' => 'warning', 'message' => 'You have not set a password yet. You cannot disconnect from Discord until you have set a password on PalWeb. Set a password first, then try again.']);

            return back();
        }

        $token = $request->user()->discord_token;

        if (! $token) {
            session()->flash('notification', ['type' => 'error', 'message' => 'Failed to disconnect Discord account. No Discord token was found.']);

            return back();
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

            if ($response->getStatusCode() == 200) {
                $request->user()->update([
                    'discord_id' => null,
                    'discord_token' => null,
                    'discord_refresh_token' => null,
                ]);

                session()->flash('notification', ['type' => 'success', 'message' => 'Disconnected your Discord account.']);

            } else {
                session()->flash('notification', ['type' => 'error', 'message' => 'Failed to disconnect Discord account. Unable to contact Discord.']);
            }

        } catch (\Exception $e) {
            session()->flash('notification', ['type' => 'error', 'message' => 'Failed to disconnect Discord account. '.$e->getMessage()]);
        }

        return back();
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
