@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('admin') }}</h1>
    </div>
@endsection

@section('content')

    <form class="splash-panel" action="{{ route('email.send') }}" method="post">
        @csrf

        <div class="sp-head">
            <div>{{ __('New Email') }}</div>
        </div>

        <x-auth-validation-errors :errors="$errors"/>

        <div class="form-field">
            <label for="subject">Subject</label>
            <input id="subject" name="subject" type="text" required>
        </div>

        <div class="form-field">
            <label for="body">Body</label>
            <textarea id="body" name="body" required></textarea>
        </div>

        <div class="form-footer">
            <button type="submit" onclick="return confirm('Are you sure you want to send this email?')">Send
            </button>
        </div>
    </form>

@endsection
