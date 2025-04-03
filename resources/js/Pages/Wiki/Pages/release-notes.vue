<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
    <div class="wiki-content-block">
        <h1 style="text-transform: none">PalWeb 2.0 BETA</h1>
        <p>Where to begin? Once again, the latest update to PalWeb is the biggest yet. By leveraging Inertia.js, I've
            turned all of PalWeb into a single-page application powered by Laravel & Vue. PalWeb 2.0 will be launched on
            July 1, 2025; what you're seeing now is the Beta version of that build, which does not include any new
            content (namely, the <b>Academy</b>). I want the community to test out the site while I build these new
            features. If you find anything that doesn't work or look as expected or have any other suggestions for
            improvement, write to me using the <b>Send Feedback</b> button in the Sidebar (you must be logged in).
        </p>
        <p>In any case, PalWeb 2.0 is already all but a completely new experience. Here's a summary of what's new. (You
            can find more information on how to use the site in the
            <Link :href="route('wiki.show', 'user-guide')">User Guide</Link>
            .)
        </p>

        <h2>Search</h2>
        <p>The <b>Search Genie</b> is still your all-in-one solution to searching the site, accessible anywhere by
            clicking on the magnifying glass in the top navigation bar or with the shortcut <b>Cmd/Ctrl+K</b>.</p>
        <p>In terms of functionality, it is now possible to filter your search to return only your Pinned items; in
            other words, you can now search your Pinned items. In fact, using the Pinned filter in the Dictionary &
            Phrasebook index pages is now the main way to view your Pinned Terms & Sentences, respectively; they are no
            longer found in the Workbench.</p>
        <p>Additionally, search results have been split up for more precise matches. Now, the search query will be
            matched against the Arabic Term by default, with the option to match against the English Gloss (i.e.
            definition) or even the Term's Root instead; this provides greater flexibility & fine-tuning of the results
            while significantly reducing undesirable clutter, a known issue in the past.</p>

        <h2>Roots</h2>
        <p>Speaking of filtering, you can now filter by letter as well! Selecting a letter in the Dictionary index page
            will return only Terms whose first Root letter is that letter or — if they have no Root — that begin with
            that letter, further emulating the experience of browsing an Arabic dictionary.</p>
        <p>Roots now have their own pages in the Dictionary as well. Click on a Term's Root in the Etymology section to
            view a page that displays all Terms belonging to that Root.</p>

        <h2>Dialogs</h2>
        <p>I have rebranded the "Annotated Transcripts" or "Texts" as <b>Dialogs</b>. Rather than hard-coded text,
            Dialogs are now collections of Sentences that exist in the Phrasebook, integrating the database on yet
            another level. These are created using the <b>SpeechMaker</b>, an admin-only tool for creating new Sentences
            & Dialogs.</p>

        <h2>Sitemap</h2>
        <p>Once again, things have moved around a bit to make navigation more intuitive:</p>
        <p>The <b>Library</b> is where you will find indices of <b>Terms</b> (<b>Dictionary</b>) & <b>Sentences</b> (<b>Phrasebook</b>),
            in addition to user-generated content, namely <b>Decks</b> & <b>Audios</b>. In turn, the <b>Community</b> is
            now simply the place to view & interact with other users, adjust your profile, etc., with the landing page
            displaying the latest user-generated content.</p>
        <p>The <b>Workbench</b> contains the <b>Deck Master</b>, which merges the functionality for building & studying
            Decks (formerly the Deck Builder & Card Viewer, respectively). It also includes the <b>Record Wizard</b>,
            which remained basically unchanged, in addition to the aforementioned <b>SpeechMaker</b>. The <b>Wiki</b> —
            where you are now — has also remained basically unchanged.</p>
        <p>In terms of navigation, everything is now accessible from the Sidebar. All functionality related to one's
            account has been moved to additional menus within the Sidebar as well. The Sidebar is called from the top
            navigation bar, which also includes a button to call the Search Genie & to change the site theme; this
            unclutters the top navigation bar & keeps the UX consistent on all devices.</p>

        <h2>Design & UX</h2>
        <p>I mean, this speaks for itself. But I will say the following:</p>
        <p>Leveraging the benefits of SPAs, some actions that should be available without interrupting the user's
            workflow (e.g. <b>Sign In</b>) are now served as modals.</p>
        <p>Now that the entire site is rendered in Vue, everything is far more interactive than before. Also, forms
            are now more responsive & some key ones (e.g. for building a Deck) essentially offer a preview of what the
            model will look like when saved.</p>

        <h1>What’s Next?</h1>
        <p>Let me start by saying that the Sidebar already gives you an idea of what's coming to PalWeb 2.0: In addition
            to the <b>Dialog</b> index page that is technically already available, the <b>Academy</b> will contain the
            <b>Quizzer</b> — an application for "quizzing" Decks, Dialogs & Skills (the building blocks of the <b>Lessons</b>)
            — & the
            <b>My Progress</b> area to view user scores, etc. I intend to have this ready by July 1 (i.e. the PalWeb 2.0
            launch).</p>
        <p>What I cannot promise, unfortunately, is to finish the <b>Lessons</b> portal by that time. While I could have
            the architecture of it ready by then, the rest involves a significant amount of content writing that is
            simply untenable in this timeframe. Instead, my goal is to launch PalWeb 2.0 as an essentially finished
            application & then spend all my time subsequently only on content writing: adding Terms to the Dictionary —
            which still has a long way to go — creating Dialogs & Lessons, filling out the Wiki, etc.
        </p>
    </div>

    <div class="wiki-content-block">
        <h1>Version History</h1>
        <ul>
            <li>01/04/2025 — PalWeb v2.0 (BETA) <b>(Current)</b></li>
            <li>15/01/2025 — PalWeb v1.2</li>
            <li>01/10/2024 — PalWeb v1.1</li>
            <li>01/07/2024 — PalWeb v1.0</li>
            <li>01/05/2024 — PalWeb v0.5 (LPA v1.4: the Anniversary Build)</li>
            <li>15/03/2024 — PalWeb v0.4 (LPA v1.3: the Land Day Build)</li>
            <li>15/01/2024 — PalWeb v0.3 (LPA v1.2: the 1998 Build)</li>
            <li>01/07/2023 — PalWeb v0.2 (LPA v1.1: the 1997 Build)</li>
            <li>01/05/2023 — PalWeb v0.1 (LPA v1.0: the May Day Build)</li>
        </ul>
    </div>
</template>
