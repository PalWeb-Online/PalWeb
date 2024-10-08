<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '01' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '1']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '01') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l1)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'01-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l1_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'01-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l1_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'01-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l1_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '02' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '2']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '02') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l2)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'02-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l2_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'02-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l2_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'02-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l2_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '03' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '3']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '03') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l3)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'03-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l3_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'03-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l3_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'03-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l3_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '04' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '4']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '04') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l4)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'04-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l4_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'04-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l4_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'04-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l4_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '05' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '5']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '05') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l5)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'05-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l5_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'05-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l5_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'05-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l5_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '06' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '6']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '06') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l6)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'06-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l6_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'06-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l6_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'06-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l6_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '07' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '7']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '07') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l7)"/>
        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'07-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l7_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'07-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l7_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'07-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l7_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '08' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '8']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '08') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l8)"/>
        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'08-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l8_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'08-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l8_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'08-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l8_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="lesson-index">
    <div class="lesson-index-head">
        <div>{{ __('lesson') . ' ' . $unit . '09' }}</div>
        <a href="{{  route('academy.lesson', [$unit, '9']) }}" class="folder">
            <img class="closed" src="{{ asset('img/folder-closed.svg') }}" alt="pin"/>
            <img class="open" src="{{ asset('img/folder-open.svg') }}" alt="pin"/>
        </a>
    </div>
    <div class="lesson-index-title">{{ __('lessons.' . $unit . '09') }}</div>
    <div class="lesson-index-body">
        <x-vue.deck component="DeckFlashcard" :deck="\App\Models\Deck::find($l9)"/>

        <div class="lesson-index-content-section-wrapper">
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'09-1') }}</div>
                <div class="lesson-index-content-section-content">{{ $l9_1 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('skill') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'09-2') }}</div>
                <div class="lesson-index-content-section-content">{{ $l9_2 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('exercise') }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('idea') }}</div>
                <div class="lesson-index-content-section-title">{{ __('lessons.'.$unit.'09-3') }}</div>
                <div class="lesson-index-content-section-content">{{ $l9_3 }}</div>
            </div>
            <div class="lesson-index-content-section">
                <div class="lesson-index-content-section-heading">{{ __('dialogue') }}</div>
            </div>
        </div>
    </div>
</div>
