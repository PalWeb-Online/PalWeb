@php
    /**
     * This view is an email that gets sent to a user when they change their password
     * @param App\Models\User $user the user who registered for an account
     */
@endphp

@extends ('layouts.mail')

@section('mail')

    <h1>{{ __("mail.subject.passwordchanged") }}</h1>
    <p>{{ __("mail.greeting") }} {{ $user->name }},</p>
    <p style="padding: 0 16px;">{{ __("mail.passwordchanged") }}</p>

@endsection
