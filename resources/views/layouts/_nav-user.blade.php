<div class="nav-user">
    <div class="nav-user-options">
        @auth
            <div class="nav-user-options-section">
                <div class="featured-title l">{{ __('dash.account') }}</div>

                <div class="nav-user-options-row">
                    <div class="nav-user-options-row-title">{{ __('dash.subscription') }}</div>
                    <div class="nav-user-options-row-content">
                        <div style="font-weight: 700">({{ auth()->user()->getRolesAsString() }})</div>
                        <a href="/billing" class="nav-user-options-row-content">{{ __('dash.manage') }}</a>
                    </div>
                </div>

                <div class="nav-user-options-row">
                    <div class="nav-user-options-row-title">{{ __('email') }}</div>
                    <div class="nav-user-options-row-content">
                        <div style="text-transform: lowercase">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="nav-user-options-row">
                    <div class="nav-user-options-row-title">{{ __('settings') }}</div>
                    <div class="nav-user-options-row-content">
                        <a href="{{ route('settings.password.edit') }}">
                            {{ auth()->user()->password ? __('password.change') : __('password.set') }}
                        </a>
                        @if(!auth()->user()->discord_id)
                            <a class="nav-user-options-row-content" href="{{ route('auth.discord') }}">Connect to
                                Discord</a>
                        @else
                            <form action="{{ route('auth.discord.revoke') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ auth()->user()->discord_token }}">
                                <button class="nav-user-options-row-content" type="submit"
                                        onclick="return confirm('Are you sure you want to unlink your Discord account?')">
                                    Unlink
                                    from Discord
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

                <div class="nav-user-options-row">
                    <div class="nav-user-options-row-title">{{ __('create') }}</div>
                    <div class="nav-user-options-row-content">
                        <a href="{{ route('decks.create') }}">Build Deck</a>
                        <a href="{{ route('audios.record') }}">Record Audios</a>
                    </div>
                </div>

                <div class="nav-user-options-row">
                    <div class="nav-user-options-row-title">{{ __('help') }}</div>
                    <div class="nav-user-options-row-content">
                        <a href="{{ route('terms.request') }}">Request Term</a>
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <div class="nav-user-options-section">
                    <div class="featured-title s">{{ __('admin') }}</div>
                    <div class="nav-user-options-row">
                        <div class="nav-user-options-row-title">{{ __('terms') }}</div>
                        <div class="nav-user-options-row-content">
                            <a href="{{ route('terms.create') }}">Create New</a>
                            <a href="{{ route('terms.todo') }}">to-Do List</a>
                        </div>
                    </div>
                    <div class="nav-user-options-row">
                        <div class="nav-user-options-row-title">{{ __('sentences') }}</div>
                        <div class="nav-user-options-row-content">
                            <a href="{{ route('sentences.create') }}">Create New</a>
                            <a href="{{ route('sentences.todo') }}">to-Do List</a>
                        </div>
                    </div>

                    <div class="nav-user-options-row">
                        <a class="nav-user-options-row-title" href="{{ route("email.create") }}">Compose Mail</a>
                    </div>
                </div>
            @endif

        @else
            <div class="nav-user-options-section">
                <div class="featured-title l"
                     style="text-transform: none">
                    enjoying
                    PalWeb?
                </div>
                <div class="nav-user-options-row" style="grid-column: span 2">
                    <a class="nav-user-options-row-title" style="font-size: 2.4rem; text-transform: none"
                       href="https://www.ko-fi.com/palweb" target="_blank">Donate via Ko-Fi</a>
                </div>
                <div class="nav-user-options-row" style="grid-column: span 2">
                    <a class="nav-user-options-row-title" style="font-size: 2.4rem; text-transform: none"
                       href="{{ route('dashboard.subscription') }}">Subscribe to PalWeb</a>
                </div>
                <div style="grid-column: span 2">
                    <p>
                        PalWeb is a completely independent, one-man project — with a little help from my friends. Of
                        course, the project incurs financial costs from IT stuff like server management, but it's also a
                        huge time investment that I can't maintain while holding down another job.</p>
                    <p>If you want to see PalWeb keep going — & expand to new horizons — consider making a one-time
                        donation on <a
                            href="https://www.ko-fi.com/palweb" target="_blank" style="font-weight: 700">Ko-fi</a>
                        or taking out a <a href="{{ route('dashboard.subscription') }}" style="font-weight: 700">Subscription</a>
                        to the site,
                        which
                        gives you access to the Academy & other perks, while also guaranteeing the project's longevity.
                    </p>
                    <p>Check the <a href="{{ route('wiki.show', 'about') }}" style="font-weight: 700">About</a> page for
                        more information about perspectives for the future of the
                        project.</p>
                </div>
            </div>
        @endauth

    </div>

    <div class="nav-user-dashboard">
        <div class="featured-title m">{{ __('dash') }}</div>

        @auth

            <div class="nav-user-dashboard-profile nav-user-toggle">
                <div class="user-avatar">
                    <div class="material-symbols-rounded">close</div>
                    <img alt="Profile Picture"
                         src="{{ asset('img/avatars/' . auth()->user()->avatar) }}"/>
                </div>
                <div class="user-nametag">
                    <div>{{ auth()->user()->name }}</div>
                    <div>({{ auth()->user()->username }})</div>
                </div>
            </div>

            <div class="nav-user-dashboard-options">
                <a href="{{ route('users.show', auth()->user()) }}" class="nav-user-dashboard-option">
                    <div>{{ __('profile') }}</div>
                    <div>view & adjust your profile</div>
                </a>
                <a href="{{ route('dashboard.workbench') }}" class="nav-user-dashboard-option">
                    <div>{{ __('workbench') }}</div>
                    <div>study your flashcards & pins</div>
                </a>
                <a href="{{ route('dashboard.subscription') }}" class="nav-user-dashboard-option">
                    <div>{{ __('subscription') }}</div>
                    <div>manage your subscription</div>
                </a>

                <form method="POST" action="{{ route('signout') }}">
                    @csrf
                    <a href="{{  route('signout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="nav-user-dashboard-option">
                        <div>{{ __('signout') }}</div>
                        <div>see you soon!</div>
                    </a>
                </form>
            </div>
        @else
            <button class="join-button nav-user-toggle">
                <div>join us!</div>
                <img class="pin" src="{{ asset('img/watermelon.svg') }}" width="48" alt="pin"/>
            </button>


            <div class="nav-user-dashboard-profile nav-user-toggle">
                <div class="user-avatar">
                    <div class="material-symbols-rounded">close</div>
                    <img alt="Profile Picture"
                         src="{{ asset('img/avatars/battix01.jpg') }}"/>
                </div>
                <div class="user-nametag">
                    <div>Guest</div>
                    <div>(this could be you!)</div>
                </div>
            </div>

            <div class="nav-user-dashboard-options">
                <a href="{{ route('signin') }}" class="nav-user-dashboard-option">
                    <div>{{ __('signin') }}</div>
                    <div>sign in to your account</div>
                </a>

                <a href="{{ route('signup') }}" class="nav-user-dashboard-option">
                    <div>{{ __('signup') }}</div>
                    <div>create a PalWeb account</div>
                </a>
            </div>
        @endauth
    </div>
</div>

