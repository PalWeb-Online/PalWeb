@extends ('layouts.mail')

@section('mail')

    <h1>{{ __("mail.subject.subscribed") }}</h1>
    <p>{{ __("mail.greeting") }} {{ $user->name }},</p>
    <p style="padding: 0 16px;">{{ __("mail.subscribed") }}</p>

@endsection
