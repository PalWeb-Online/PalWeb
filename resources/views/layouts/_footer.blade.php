<div class="footer-container">
    <div class="footer-social">
        <a href="https://github.com/PalWeb-Online" target="_blank">
            <img src="{{ asset('/img/github.svg') }}" alt="GitHub"/>
        </a>
        <a href="https://www.youtube.com/@PalWebTV" target="_blank">
            <img src="{{ asset('/img/youtube.svg') }}" alt="YouTube"/>
        </a>
        <a href="https://twitter.com/palweb_app" target="_blank">
            <img src="{{ asset('/img/twitter.svg') }}" alt="Twitter"/>
        </a>
        <a href="https://www.instagram.com/abdulbaha.xyz" target="_blank">
            <img src="{{ asset('/img/instagram.svg') }}" alt="Instagram"/>
        </a>
        <a href="https://discord.gg/3Wf7Q6RCjV" target="_blank">
            <img src="{{ asset('/img/discord.svg') }}" alt="Discord"/>
        </a>
    </div>

    <img class="footer-image" src="{{ asset('/img/map.svg') }}" alt="Logo"/>

    <div class="footer-logo">
        <div class="footer-title">PalWeb!</div>
        <div style="padding-inline-start: 0.2rem">the Web of Palestinian Arabic</div>
    </div>


    <div class="footer-links">
        <div class="footer-links-title">About</div>
        <a href="{{ route('wiki.show', 'faq') }}">FAQ</a>
        <a href="{{ route('wiki.show', 'about') }}">PalWeb</a>
        <a href="{{ route('wiki.show', 'ajp') }}">Spoken Arabic</a>
    </div>
    <div class="footer-links">
        <div class="footer-links-title">Donate</div>
        <a href="{{ route('dashboard.subscription') }}">{{ __('footer.subscribe') }}</a>
        <a href="https://www.patreon.com/palweb" target="_blank">Patreon</a>
        <a href="https://www.ko-fi.com/palweb" target="_blank">Ko-fi</a>
    </div>

    <div class="footer-bottom">by R. Adrian · adrian@abdulbaha.xyz</div>
</div>
