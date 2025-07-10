@extends ('layouts.mail')

@section('mail')
    <h1 style="font-size: 28px">{{ $subject }}</h1>
    <p style="font-size: 18px">{{ __('mail.greeting') }} {{ $user->name }},</p>
    <p style="padding: 0 16px;">{!! $body !!}</p>
    <div style="padding: 16px; text-align: center">
        <a style="border-radius: 16px; color: white; text-decoration: none; font-weight: 700; padding: 16px 32px; margin: 0 auto; background: #4313aa;"
           href="https://palweb.app">Visit the Site</a>
    </div>
@endsection
