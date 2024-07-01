@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dash.account') }}</h1>
    </div>
@endsection

@section('content')

    <form class="splash-panel" id="avatarForm" method="POST" action="{{ route('settings.avatar.update') }}">
        @method('PATCH')
        @csrf

        <div class="sp-head">
            <div>{{ __('profile.avatar.change') }}</div>
            <p>All images are from <a href="https://commons.wikimedia.org">Wikimedia Commons</a> or the <a
                    href="https://www.palestineposterproject.org/">Palestine Poster Project</a>, or are my own work,
                with the exception of the notable works of Sliman Mansour.</p>
        </div>

        <x-auth-validation-errors :errors="$errors"/>

        <div class="avatar-grid">
            @foreach($avatars as $avatar)
                <button type="button" onclick="submitForm('{{ $avatar }}')">
                    <img src="{{ asset('img/avatars/' . $avatar) }}" alt="Profile Picture">
                </button>
            @endforeach
            <input type="hidden" id="avatarInput" name="avatar">
        </div>
    </form>

    <script>
        function submitForm(avatar) {
            let avatarForm = document.getElementById('avatarForm');
            let avatarInput = document.getElementById('avatarInput');
            avatarInput.value = avatar;
            avatarForm.submit();
        }
    </script>

@endsection
