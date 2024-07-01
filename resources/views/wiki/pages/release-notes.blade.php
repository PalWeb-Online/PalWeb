<x-page-head title="Release Notes"
             blurb="Welcome to PalWeb! Here's what's new.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'about')">{{ __('release-notes') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1 style="text-transform: none">PalWeb v1.0</h1>
    <p>PalWeb is finally here! After a year of evolution, the Learn Palestinian Arabic website snowballed into something
        much greater. It quickly became clear that a new banner was necessary to represent the qualitative leap from
        a static informational page to an interactive network of words & word lovers.</p>
    <p>While the site's design has been completely reimagined, there's not too much in the way of new features since the
        Anniversary Build of the Learn Palestinian Arabic site, the final stable version in the prehistory of PalWeb.
        That's because all the focus in the last two months has been on carefully redesigning every corner of the site &
        cleaning up the codebase in order to publicly launch PalWeb 1.0 as a public repository on GitHub. Most of the
        changes are therefore structural:</p>
    <ul>
        <li>Dashboard is now overlain over the current view, with links to one's public Profile & private Workbench.
            Account options appear directly within this Dashboard view.
        </li>
        <li>Units now have 9 Lessons, instead of 3 Lessons with 3 Modules each. Lessons now have a single vocabulary
            list in the form of an interactable Deck.
        </li>
    </ul>
</div>

<div class="doc-section">
    <h1>What’s Next?</h1>
    <p>Here are some of the things on the development schedule for the next six months.</p>
    <ul>
        <li><b>July</b> — Launch PalWeb & public repository. Support & bug fixes, etc. Content writing for Wiki.
            Commission new avatars & badge art from Palestinian artists.
        </li>
        <li><b>August</b> — Port RecordWizard extension. Content writing for Wiki. Record missing audios. Add 250 terms
            to Dictionary.
        </li>
        <li><b>September</b> — Build Flashcard Portal. Add 250 terms to Dictionary</li>
        <li><b>October-December</b> — Rebuild Academy with interactive Activities & User Progress, etc.</li>
    </ul>
    <p>Based on where I am by January 1st, we'll see what the following six months will look like. If all of the above
        goes smoothly, then I can dedicate those six months exclusively to adding terms to the Dictionary & — finally —
        to finishing the actual Academy content. As you can imagine, the reason why that has kept getting delayed is
        that it's not practical to further flesh out something now that I actually need to rebuild.</p>
    <p>I'm working toward PalWeb 2.0 next July 1st. All the help I can get until then will just help me reach that goal
        faster! Read the <a href="{{ route('wiki.show', 'contributing') }}">Contributing</a> page if you'd like to help
        out!</p>
    <p>Finally — I'm putting this at the end because it might be a little ambitious — I'm hoping that PalWeb 2.0 can
        launch alongside LangWeb 1.0, a framework for other language-learning web applications following the integrated
        PalWeb model. Read the <a href="{{ route('wiki.show', 'about') }}">About</a> page for more on that long-term
        vision!</p>
</div>

<div class="doc-section">
    <h1>Version History</h1>
    <ul>
        <li>01/07/2024 — PalWeb v1.0.0 <b>(Current)</b></li>
        <li>01/05/2024 — PalWeb v0.5.0 (LPA v1.4.0: the Anniversary Build)</li>
        <li>15/03/2024 — PalWeb v0.4.0 (LPA v1.3.0: the Land Day Build)</li>
        <li>15/01/2024 — PalWeb v0.3.0 (LPA v1.2.0: the 1998 Build)</li>
        <li>01/07/2023 — PalWeb v0.2.0 (LPA v1.1.0: the 1997 Build)</li>
        <li>01/05/2023 — PalWeb v0.1.0 (LPA v1.0.0: the May Day Build)</li>
    </ul>
</div>
