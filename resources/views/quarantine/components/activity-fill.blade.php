<div class="activity-fill" style="{{ isset($img) ? 'flex-flow:column; row-gap: 0.8rem' : '' }}">
    <p>{{ $que }}</p>
    {!! isset($img) ? '<img src="' . $img . '">' : '' !!}
    <div>
        <input type="text" data-ans="{{ $ans }}" maxlength="{{ strlen($ans) }}">
        <button class="material-symbols-rounded">done</button>
    </div>
</div>
