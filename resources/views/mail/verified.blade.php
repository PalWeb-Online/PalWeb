@extends ('layouts.mail')

@section('mail')

    <h1>{{ __("mail.subject.verified") }}</h1>
    <p>{{ __("mail.greeting") }} {{ $user->name }},</p>
    <p style="padding: 0 16px;">{{ __("mail.verified") }}</p>
    {{--    <p>Awesome — thank you for verifying your account! You're all set to start learning Palestinian Arabic!</p>--}}
    {{--    <p>We invite all verified users to join our Discord server to get to know others interested in Palestine &--}}
    {{--        Palestinian Arabic — just click the link below. See you there!</p>--}}


    <div style="padding: 16px; text-align: center">
        <a style="border-radius: 16px; color: white; text-decoration: none; font-weight: 700; padding: 16px 32px; margin: 0 auto; background-color: #4313aa;"
           href="https://discord.gg/3Wf7Q6RCjV">Join Discord Server</a>
    </div>
@endsection
