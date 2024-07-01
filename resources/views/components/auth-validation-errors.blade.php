@props(['errors'])

@if ($errors->any())
    <div class="form-errors">
        <div>{{ __('validation.error') }}</div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
