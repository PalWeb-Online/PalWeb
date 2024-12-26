<x-page-head title="{{ __('workbench') }}" blurb="Learn to use database-powered Palestinian Arabic learning tools.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'workbench')">{{ __('workbench') }}</x-link>
</x-page-head>

<div class="doc-section">
    <p>Your <b>Workbench</b> is the area of your <b>Dashboard</b> where you will see all of your <b>Pinned</b> items,
        namely Decks, Terms & Sentences. Also, the <b>Workbench</b> provides three tools to create, study & share
        language material on PalWeb: the <b>Deck Builder</b>, the <b>Flashcard Master</b> & the <b>Record Wizard</b>.
    </p>

    <h2>Deck Builder</h2>
    <p>The <b>Deck Builder</b> is a tool to create Decks by grouping Terms from the PalWeb Dictionary. You'll be able to
        study these Decks as flashcards & share them with others!</p>
    <p>After giving your Deck a name & an optional description, simply start typing in the search bar (in Arabic or in
        English) to find the Terms you would like to add to the Deck. Select the Term you would like to add from the
        results to add it. You may remove any item from the list by clicking on the trash icon. Click & drag any of the
        items in the list to reorder it.</p>
    <p>Next to each Term you've selected, you will see a dropdown list containing all of the Term's definitions. By
        default, the Term's first definition will be displayed whenever the Deck is viewed, but you may select a
        different one to be shown by default. (When you study the Deck as flashcards, all definitions of the Term are
        shown on the card.)</p>
    <p>Once you're satisfied with the Deck, create it! You will be able to return to the <b>Deck Builder</b> at any time
        to adjust the list if you'd like. Note that by default every Deck you create goes into the PalWeb Deck Library,
        where others may view it, pin it & study it. If you would like to keep your Deck private, so that only you may
        view & interact with it, check "Private" before creating the Deck; you may change the Deck's privacy status
        later at any time.</p>

    <h2>Flashcard Master</h2>
    <p>The <b>Flashcard Master</b> is a tool to study your Pinned Decks as flashcards. It is divided into two areas:</p>
    <ol>
        <li><b>Decks.</b> In the Decks page, click on one of your Pinned Decks to select it, then use the hands in the
            navigation
            bar to proceed to the next step.
        </li>
        <li><b>Cards.</b> You will be presented with the Terms in the Deck as a carousel of Cards that you can swipe
            through (you can also use the keyboard to control the application). Use the options panel to change how you
            would like to view the Cards. Experiment with the different options to find the settings most suitable for
            you!
        </li>
    </ol>

    <h2>Record Wizard</h2>
    <p>The <b>Record Wizard</b> is a tool to record yourself speaking Arabic. Its primary purpose is as a means to
        source audio samples of the many possible pronunciations of the Terms in the PalWeb Dictionary; this is for
        documentation purposes, but is also practical for learners of Arabic dialects to have something to listen to,
        practice with & emulate.</p>
    <p>But the <b>Record Wizard</b> is not necessarily only for native speakers to use. If you speak Palestinian Arabic
        well, or even if you just want to practice, you are welcome to use the Record Wizard too. All Audio files are
        associated with a Speaker whose Fluency level is indicated everywhere that they appear, so it will always be
        clear which Audios belong to native speakers as opposed to other learners of Arabic.</p>
    <p>On opening the <b>Record Wizard</b>, your browser may ask you for permission to use of your mic; you must allow
        this to proceed. The <b>Record Wizard</b> is divided into four areas:</p>
    <ol>
        <li><b>Speaker.</b> Create your Speaker profile, if you haven't already. Test your mic before proceeding.</li>
        <li><b>Queue.</b> Generate lists of Terms to add to the Queue of items to be recorded.</li>
    </ol>
</div>
