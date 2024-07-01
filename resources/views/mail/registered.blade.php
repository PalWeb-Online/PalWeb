@extends ('layouts.mail')

@section('mail')

    <h1>{{ __("mail.subject.registered") }}</h1>
    <p>{{ __("mail.greeting") }} {{ $user->name }},</p>
    <p style="padding: 0 16px;">{{ __("mail.registered") }}</p>

    <div style="padding: 16px; text-align: center">
        <a style="border-radius: 16px; color: white; text-decoration: none; font-weight: 700; padding: 16px 32px; margin: 0 auto; background-color: #4313aa;"
           href="https://www.abdulbaha.xyz/dashboard" target="_blank">Verify My Email</a>
    </div>
@endsection
