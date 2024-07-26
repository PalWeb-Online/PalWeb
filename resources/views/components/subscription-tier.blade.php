@php
    $current = 'guest';

    if (auth()->check()) {
        $current = 'hobbyist';

        if(in_array(auth()->user()->getRolesAsString(), ['student', 'admin'])) {
            $current = 'student';
        }
    }

    $unlocked = false;

    if ($tier === 'guest' && in_array($current, ['hobbyist', 'student'])) {
        $unlocked = true;
    } elseif ($tier === 'hobbyist' && $current === 'student') {
        $unlocked = true;
    }

    $unlockable = false;

    if ($tier === 'hobbyist' && $current === 'guest') {
        $unlockable = true;
    } elseif ($tier === 'student' && in_array($current, ['hobbyist', 'guest'])) {
        $unlockable = true;
    }
@endphp

<div class="tier-item-wrapper">
    @if($unlocked)
        <div class="tier-item-unlocked">
            <div>unlocked</div>
        </div>
    @endif
    <div class="tier-item {{ $unlockable ? 'unlockable' : '' }}">
        <div class="tier-head">{{ $tier }}</div>
        <div class="tier-body">
            <div>Access to <b>Dictionary</b></div>
            <div>Access to <b>Wiki</b></div>
            <div class="{{ in_array($tier, ['hobbyist', 'student', 'admin']) ? '' : 'disabled' }}">
                <b>Pin</b> to your <b>Workbench</b>
            </div>
            <div class="{{ in_array($tier, ['hobbyist', 'student', 'admin']) ? '' : 'disabled' }}">
                <b>Request</b> Terms
            </div>
            <div class="{{ in_array($tier, ['hobbyist', 'student', 'admin']) ? '' : 'disabled' }}">
                Access to <b>Phrasebook</b>
            </div>
            <div class="{{ in_array($tier, ['hobbyist', 'student', 'admin']) ? '' : 'disabled' }}">
                Access to <b>Deck Library</b>
            </div>
            <div class="{{ in_array($tier, ['student', 'admin']) ? '' : 'disabled' }}">
                Access to <b>Explore</b> Portal
            </div>
            <div class="{{ in_array($tier, ['student', 'admin']) ? '' : 'disabled' }}">
                Access to <b>Academy</b></div>
            <div class="{{ in_array($tier, ['student', 'admin']) ? '' : 'disabled' }}">
                (Soon) Study <b>Flashcards</b>
            </div>
            <div class="{{ in_array($tier, ['student', 'admin']) ? '' : 'disabled' }}">
                Support the Project
            </div>
        </div>
        <div class="tiers-pricing">{{ $tier === 'student' ? '$12/m $80/y' : 'FREE' }}</div>

        @if($tier === $current)
            <div class="tiers-action">{{ 'current' }}</div>
        @elseif($current === 'guest')
            <a href="{{ route('signin') }}" class="tiers-action">{{ __('signin') }}</a>
        @elseif($unlocked)
            <div class="tiers-action">{{ 'unlocked' }}</div>
        @else
            <a href="/billing" class="tiers-action">{{ 'upgrade' }}</a>
        @endif
    </div>
</div>
