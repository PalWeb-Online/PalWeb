<x-page-head title="Release Notes"
             blurb="Welcome to PalWeb! Here's what's new.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'about')">{{ __('release-notes') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1 style="text-transform: none">PalWeb v1.2</h1>
    <p>As usual, the development of PalWeb proceeds with a mind of its own. Sometimes, what seems feasible at first
        turns out to be far more challenging & time-consuming than expected, while daunting tasks come together
        naturally & everything falls quickly into place. Meanwhile, the political moment we live in permits no leisure
        or frivolity; for all the time I spend on PalWeb, I spend as much, for better or worse, on building political
        power where it is needed to bring an end to this madness. So, I'm sorry for my working pace, which never seems
        to reach the rate I envision.</p>
    <p><b>PalWeb v1.1</b> is the ribbon-cutting for a variety of improvements introduced over the course of the summer,
        in addition to introducing for the first time a key promised feature. Here's what's new & improved:</p>
    <ul>
        <li>You can now study Decks through the <b>Flashcard Portal</b>, with several options to customize how you view
            flashcards.
        </li>
        <li>Turns most Deck, Term & Sentence objects into reactive Vue components for more intuitive interaction;
            pinning, etc. no longer triggers a page refresh.
        </li>
        <li>Renders most dropdown menus through Vue to place them dynamically in relation to their trigger &
            the available space, preventing page overflow.
        </li>
        <li>Various design improvements in the Dictionary, namely in the display of attributes at the top & inflections
            further down the page.
        </li>
        <li>Added a "Random Term" prompt to Dictionary pages to jump more seamlessly between pages.</li>
        <li>Reconfigured how Sentences are built, tying them to the database & making them dynamic.</li>
        <li>Fixes an issue where the Billing portal was hard to find.</li>
        <li>Sorting now works as intended in the Workbench.</li>
        <li>Word of the Day has been fixed (finally).</li>
        <li>Updated front page.</li>
    </ul>

    <p>Of course, there's still a lot of work left to do. For the rest of the year, I will be working on creating an
        in-house application for recording & managing audio files. I don't want to just whip up something quickly,
        because this would be the basis of the audio file management functionality of <b>LangWeb</b>. In the remaining
        time, I will continue supporting the site, adding Terms to the Dictionary, etc. Proper work on the
        <b>Academy</b> will begin in January & would be completed by summer.</p>
</div>

<div class="doc-section">
    <h1>Version History</h1>
    <ul>
        <li>01/10/2024 — PalWeb v1.1.0 <b>(Current)</b></li>
        <li>01/07/2024 — PalWeb v1.0.0</li>
        <li>01/05/2024 — PalWeb v0.5.0 (LPA v1.4.0: the Anniversary Build)</li>
        <li>15/03/2024 — PalWeb v0.4.0 (LPA v1.3.0: the Land Day Build)</li>
        <li>15/01/2024 — PalWeb v0.3.0 (LPA v1.2.0: the 1998 Build)</li>
        <li>01/07/2023 — PalWeb v0.2.0 (LPA v1.1.0: the 1997 Build)</li>
        <li>01/05/2023 — PalWeb v0.1.0 (LPA v1.0.0: the May Day Build)</li>
    </ul>
</div>
