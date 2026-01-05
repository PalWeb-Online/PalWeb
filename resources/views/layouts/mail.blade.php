<body
    style="background: #FFEBEC; font-family: 'Helvetica Neue', sans-serif; max-width: 720px; margin: 0 auto; padding: 0 0 16px; border-spacing: 0">

<div style="padding: 0 160px; margin: 0">
    <img src="{{ asset('img/logo_circle_watermelon.svg') }}" width="100%" alt="PalWeb Logo"/>
</div>

<div
    style="padding: 16px 32px; background: #CBE6DF">

    @yield('mail')

    <p style="margin-top: 16px">{{ __("mail.farewell") }},</p>
    <p><b>— R. Adrian</b></p>
</div>

<div style="padding: 24px 24px 8px; background: #005244; color: white">
    <h1 style="margin: 12px 0">PalWeb</h1>
    <h3 style="margin: 12px 0">the Web of Palestinian Arabic</h3>
    <div style="border-top: 4px solid #FFA3AB"></div>
    <div style="margin: 16px 0; text-align: center">
        <a href="https://github.com/PalWeb-Online" style="text-decoration: none">
            <img src="{{ asset('img/github.png') }}" alt="GitHub"/>
        </a>
        <a href="https://www.youtube.com/@PalWebTV" style="text-decoration: none">
            <img src="{{ asset('img/youtube.png') }}" alt="YouTube"/>
        </a>
        <a href="https://x.com/palweb_app" style="text-decoration: none">
            <img src="{{ asset('img/twitter.png') }}" alt="Twitter"/>
        </a>
        <a href="https://www.instagram.com/palweb.app" style="text-decoration: none">
            <img src="{{ asset('img/instagram.png') }}" alt="Instagram"/>
        </a>
        <a href="https://discord.gg/3Wf7Q6RCjV" style="text-decoration: none">
            <img src="{{ asset('img/discord.png') }}" alt="Discord"/>
        </a>
    </div>
</div>

</body>
