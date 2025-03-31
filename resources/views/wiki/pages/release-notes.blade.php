<x-page-head title="Release Notes"
             blurb="Welcome to PalWeb! Here's what's new.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'about')">{{ __('release-notes') }}</x-link>
</x-page-head>

<div class="wiki-container">
    <h1 style="text-transform: none">PalWeb v1.2</h1>
    <p><b>PalWeb v1.2</b> is probably the most significant update to the site since I first transitioned to the Laravel
        framework in 2022. And yet in retrospect it will probably be the least significant of the next few updates to
        come. More & more of the application is being built using Vue, which means a much smoother user experience. But
        more importantly, I've established a foundation to build the interactive activities that I've envisioned for the
        <b>Academy</b> portal. Starting with this update, I can finally work on it in earnest.</p>

    <p><b>PalWeb v1.2</b> introduces a handful of significantly improved & brand-new features. Here is a summary of
        these new features. If you'd like more information on how to use these tools, see the <a
            href="{{ route('wiki.show', 'user-guide') }}">User Guide</a>.
    </p>

    <h2>Search Genie</h2>
    <p>The <b>Search Genie</b> is your all-in-one solution to searching the site, accessible anywhere by clicking on the
        button prompt in the top-right corner, or with the shortcut <b>Cmd/Ctrl+K</b>. Quickly switch between types of
        results — Terms, Sentences or Decks — & apply filters to get specific results in real time. Any filters you've
        applied for Terms will persist while searching for Sentence & Decks, so you can search for Decks with a specific
        term in it, or get all Sentences with a pseudo-verb.</p>
    <p>Moreover, the <b>Search Genie</b> is context-aware. Open it in the <b>Deck Builder</b> to add Terms to your Deck.
        Open it in the <b>Card Reader</b> to pin new Decks for studying. Use the <b>Search Genie</b> wherever you see
        the button prompt to see what you can do with it! By the way, since searching is now handled holistically by the
        <b>Search Genie</b>, I have removed the search bar from the <b>Deck Library</b> & the <b>Phrasebook</b>,
        replacing it with a prompt to open the <b>Search Genie</b>.</p>

    <h2>Deck Builder & Card Viewer</h2>
    <p>Both of these tools were already present on the site, but they looked radically different from how they do now,
        especially the <b>Deck Builder</b>. Instead of accessing these tools by interacting with a Deck on the main
        site, you can simply boot up either of them to view a page with all the Decks available for you to use: your
        created Decks to edit in the <b>Deck Builder</b> or your pinned Decks to study in the <b>Card Viewer</b>. Use
        the <b>Search Genie</b> within these tools to get everything you need on the spot, as explained above.</p>

    <h2>Record Wizard & Audio Library</h2>
    <p>The <b>Record Wizard</b> is by far the biggest new feature in this update & a game-changer at that, if I do say
        so myself. I want to give credit to
        <a href="https://lingualibre.org/wiki/LinguaLibre:Main_Page" target="_blank">Lingua Libre</a>,
        whose Record Wizard I used to create the 3,000-odd audio files that were featured first on Wiktionary & then on
        PalWeb. Their Record Wizard, published on a public repository on GitHub, served as the basis for mine. Again,
        see the <a href="{{ route('wiki.show', 'user-guide') }}">User Guide</a> for more information about the
        functionality of the <b>Record Wizard</b> itself.</p>
    <p>I have created an all-new <b>Audio Library</b> within the <b>Community</b> portal. Like the other content
        libraries on the site, its main page is a paginated index of all the Audios in the database, with a few filters
        that allow you to view all Audios by Speakers of a given Dialect, from a given Location, with a given fluency
        level, etc. <b>Speaker</b> profiles live within the <b>Audio Library</b> too.</p>
    <p>In terms of the backend: Instead of each Pronunciation having a single Audio (in fact there was no Audio model;
        this relationship didn't exist in the backend), now one Pronunciation may have an unlimited number of associated
        Audios. In the Dictionary, the first two Audios — sorted by Speaker fluency — are displayed under their
        corresponding Pronunciation, while all Audios for a given Term may be viewed by selecting "See All Audios" from
        the Context Actions menu.</p>

    <h2>Sitemap</h2>
    <p>All of these new features have necessitated a restructuring of certain areas of the site:</p>
    <ul>
        <li>the <b>Deck Library</b> has been trimmed down by moving the featured Decks to the <b>Community</b> hub.</li>
        <li>the new <b>Community</b> hub displays the latest Decks & Audios, giving a shout-out to the site's most
            prolific Pals too.
        </li>
        <li>the <b>Workbench</b> has been streamlined to highlight all these new tools.</li>
    </ul>

    <h1>What’s Next?</h1>
    <p>I previously promised that I would finally get to work on the <b>Academy</b> portal by January & I'm happy to
        confirm that this is still the case. In the next six months, I'm going to focus on adding activities to PalWeb.
        I will actually be starting by adding activity features like quizzes to the <b>Card Viewer</b>, as these will be
        the basis of the more complex activities I plan to create for the <b>Academy</b>. Naturally, this will require
        me to build a scoring system & a way to track what activities the user has completed. As is often the case with
        PalWeb, this will be a multi-faceted endeavor, but I'm hoping to complete it in about three months.</p>
    <p>Once that is all set up, the core architecture of the site could be considered complete, meaning I will be able
        to proceed to working primarily on content creation, namely the content of the <b>Academy</b> portal. While I
        already have most of the curriculum planned out, I hadn't set out to do this yet because it didn't make sense to
        do so before finishing most of the functionality that I would need to leverage in that area of the site.</p>
</div>

<div class="wiki-container">
    <h1>Version History</h1>
    <ul>
        <li>15/01/2025 — PalWeb v1.2.0 <b>(Current)</b></li>
        <li>01/10/2024 — PalWeb v1.1.0</li>
        <li>01/07/2024 — PalWeb v1.0.0</li>
        <li>01/05/2024 — PalWeb v0.5.0 (LPA v1.4.0: the Anniversary Build)</li>
        <li>15/03/2024 — PalWeb v0.4.0 (LPA v1.3.0: the Land Day Build)</li>
        <li>15/01/2024 — PalWeb v0.3.0 (LPA v1.2.0: the 1998 Build)</li>
        <li>01/07/2023 — PalWeb v0.2.0 (LPA v1.1.0: the 1997 Build)</li>
        <li>01/05/2023 — PalWeb v0.1.0 (LPA v1.0.0: the May Day Build)</li>
    </ul>
</div>
