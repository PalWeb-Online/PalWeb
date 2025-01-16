<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function __construct(protected FlasherInterface $flasher) {}

    /**
     * Discord redirect back to application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        $error = request('error') ?? null;
        $errorDescription = request('error_description') ?? null;

        if ($error) {
            Log::error("Discord OAuth error: $error - $errorDescription");
            $this->flasher->addError('You canceled the Discord authentication process.');

            return redirect('/');
        }

        try {
            $discordUser = Socialite::driver('discord')->user();
            $user = auth()->user();

            if ($user) {
                $this->updateUser(auth()->user(), $discordUser);
                $this->checkMembership(auth()->user(), $discordUser->token);

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
                        $this->checkMembership(auth()->user(), $discordUser->token);

                    } else {
                        $this->flasher->addWarning('We couldn\'t sign you in, because there is no PalWeb account that is connected to or has the same email as the provided Discord account. Please sign in normally first & connect your Discord account through the Dashboard before trying to log in this way.');

                        return redirect('/');

                        //                        $user = $this->createUser($discordUser);

                        //                        Auth::login($user);
                        //                        $this->flasher->addFlash('info', __('registered.message', ['user' => $user->name]), __('registered.message.head'));

                        //                        $this->checkMembership($user, $discordUser->token);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to authenticate with Discord: '.$e->getMessage());

            $this->flasher->addError('Failed to authenticate with Discord.');
        }

        return redirect('/');
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

    public function revoke(Request $request)
    {
        if (! auth()->user()->password) {
            $this->flasher->addWarning('You have not set a password for this account. You cannot disconnect from Discord until you have set a password on PalWeb. Please set a password first, then try again.');

            return redirect('/');
        }

        $token = $request->input('token');

        $client = new Client;
        $response = $client->post('https://discord.com/api/oauth2/token/revoke', [
            'form_params' => [
                'token' => $token,
                'client_id' => env('DISCORD_CLIENT_ID'),
                'client_secret' => env('DISCORD_CLIENT_SECRET'),
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            auth()->user()->update([
                'discord_id' => null,
                'discord_token' => null,
                'discord_refresh_token' => null,
            ]);

            // Token revocation was successful
            $this->flasher->addSuccess('Disconnected your Discord account.');

            return redirect('/');
        } else {
            // Handle errors or unsuccessful revocation
            $this->flasher->addError('Failed to disconnect Discord account.');

            return redirect('/');
        }
    }

    /**
     * Redirect to Discord.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        if (auth()->user() && auth()->user()->discord_id) {
            // if User exists, is logged in & is connected

            $this->flasher->addWarning('Your Discord account is already connected.');

            return redirect('/');
        } else {
            return Socialite::driver('discord')->scopes(['identify', 'email', 'guilds'])->redirect();
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
