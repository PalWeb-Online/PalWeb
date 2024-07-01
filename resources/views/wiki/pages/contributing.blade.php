<x-page-head title="{{ __('contributing') }}">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'contributing')">{{ __('contributing') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>for Developers</h1>
    <p>PalWeb is now open source! Check out the public repository on GitHub!</p>
    <p>PalWeb is built on Laravel & primarily uses the Blade templating engine to render views, but I will probably have
        to increasingly make use of Vue to achieve the interactivity necessary to build the next stages of the project.
        If you're a developer with knowledge of Javascript & PHP, especially in the use of the Vue & Laravel frameworks,
        please get in touch (<b>adrian@abdulbaha.xyz</b>) if you'd like to contribute to the project. You can get
        started right away by cloning the public repository on GitHub.
    </p>
    <p>Another thing I haven't investigated too much but will probably need help with is the porting of the
        <a href="https://github.com/lingua-libre/RecordWizard/tree/master" target="_blank">RecordWizard</a> MediaWiki
        extension, created for Lingua Libre, the site through which audio recordings are currently produced. Apparently
        it's mostly Javascript, so I don't see why it couldn't be adapted to a non-MediaWiki site.</p>
    <p>Anyway, here's what's on the horizon for PalWeb in the near future:</p>
    <ul>
        <li>the Flashcard Portal, which would allow users to study their Decks as interactive flashcards.</li>
        <li>the Activities within the PalWeb Academy</li>
        <li>User Progress tracking</li>
        <li>RecordWizard Port</li>
    </ul>
</div>

<div class="doc-section">
    <h1>for Palestinian Arabic Speakers</h1>
    <p>I'm primarily looking for people who would be willing to volunteer to record audio samples of terms & sentences
        for the Dictionary, so please get in touch (<b>adrian@abdulbaha.xyz</b>) if that sounds like you.</p>
</div>

<div class="doc-section">
    <h1>Everyone Else</h1>
    <p>Even if you're not a developer or an Arabic speaker, there's plenty you can do to contribute to the project.
        Just by using the site, you help to build it! <a href="{{ route('decks.create') }}" target="_blank">Building
            Decks</a> for
        yourself & others to use & <a href="{{ route('terms.request') }}" target="_blank">Requesting
            Terms</a> that you notice are missing from the Dictionary are two practical ways to contribute. Most
        importantly, though, don't forget to spread the word!</p>
</div>
