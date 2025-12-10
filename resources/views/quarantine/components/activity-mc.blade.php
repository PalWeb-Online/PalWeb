<div class="activity-select">
    <p>{{ $que }}</p>
    <div class="activity-select-choices {{ isset($shuffle) ? 'shuffle' : '' }}">
        <button onclick="{{ $ans == 'a' ? 'fx_correct' : 'fx_incorrect' }}.play()">{{ $a }}</button>
        <button onclick="{{ $ans == 'b' ? 'fx_correct' : 'fx_incorrect' }}.play()">{{ $b }}</button>
        @isset($c)
            <button onclick="{{ $ans == 'c' ? 'fx_correct' : 'fx_incorrect' }}.play()">{{ $c }}</button>
        @endisset
        @isset($d)
            <button onclick="{{ $ans == 'd' ? 'fx_correct' : 'fx_incorrect' }}.play()">{{ $d }}</button>
        @endisset
    </div>
</div>
