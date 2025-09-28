<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
    <div class="wiki-content-block">
        <h1>Introduction</h1>
        <p>Your <b>Workbench</b> is the area of your <b>Dashboard</b> where you will see all of your pinned items,
            namely
            Decks, Terms & Sentences. The <b>Workbench</b> provides three tools to create, study & share language
            materials
            on PalWeb:</p>
        <ul>
            <li><a href="#deck-master">Deck Master</a></li>
            <li><a href="#record-wizard">Record Wizard</a></li>
        </ul>
        <p>How these tools are used is explained in the section of this page corresponding to each.</p>
    </div>

    <div class="wiki-content-block" id="deck-master">
        <h1>Deck Master</h1>
        <p>The <b>Deck Master</b> is a tool for building & studying Decks made up of Terms from the PalWeb Dictionary.
            The <b>Deck Master</b> has two modes: <b>Build</b> Mode & <b>Study</b> Mode.</p>
        <!--        Once created, you can study these Decks in the <b>Card Viewer</b> & share them with others.-->

        <h2>Build Mode</h2>
        <p>When entering the Deck Master in this mode, on the <b>Select</b> page you will see an array of all the Decks
            you've created. (If this is your first time creating a Deck, the array will be empty.) If you would like to
            <b>create a Deck</b>, simply click the Create button to proceed to the <b>Build</b> page; the Deck Master
            will stage an empty Deck for you. Alternatively, if you would like to <b>edit a Deck</b>, click on any of
            the available Decks in the array to select it; the Create button will turn into an Edit button that you will
            click to proceed.</p>
        <p>If you access the Deck Master by selecting "Edit Deck" from a Deck's Context Actions menu, you will be sent
            directly to the <b>Build</b> page with the Deck staged. You can always return to the <b>Select</b> page from
            the <b>Build</b> page at any point to choose a different Deck to work on.</p>
        <p>On the <b>Build</b> page, you will see a mockup of the Deck you've selected in the previous step, or of an
            empty Deck if you're creating a new one. Aside from the Terms they contain, Decks only have two fillable
            fields: the Deck's name — which is required — & an optional description.</p>
        <p>After filling out these fields, use the
            <Link :href="route('wiki.show', 'user-guide') + '#search-genie'">Search
                Genie
            </Link>
            to find the Terms you would like to add to the Deck. Select the Term you would like to add from
            the results to add it. You may remove any item from the list by clicking on the trash icon. Click & drag any
            of
            the items in the list to reorder it. Don't be afraid to play around with things, as your changes will not be
            saved until you click the "Save" button.
        </p>
        <p>Each Term you've selected will include a dropdown list containing all of the Term's Glosses (i.e.
            definitions).
            By default, the Term's first Gloss will be displayed whenever the Deck is viewed, but you may select a
            different
            one to be shown by default. (When studying a Deck in the <b>Card Viewer</b>, all Glosses of a Term are shown
            on
            its card.)</p>
        <p>Once you're satisfied with the Deck, click "Save" to save your changes; this will create the Deck, if
            applicable.
            You can now click "View" to open the Deck's page in a new tab & see your edits. If you don't want to save
            your
            changes, you can simply exit the <b>Build</b> page without saving. You can also click "Reset" to revert the
            Deck
            to its original state before the current editing session. After saving, you can continue editing the Deck,
            or
            return to the <b>Select</b> page to edit another one. (If you've just created a Deck & would like to create
            yet
            another one, return to the <b>Select</b> page to deselect the Deck you've just created, then navigate to the
            <b>Build</b> page again.)</p>
        <p>If you would like to delete the staged Deck, simply click the "Delete" button. You can also delete any Deck
            you've created from outside the <b>Deck Master</b> simply by interacting with it & selecting "Delete Deck"
            from
            the Context Actions menu.</p>
        <p>By default, every Deck you create goes into the Deck Library, where others may view it, pin it, study it,
            export
            it, etc. If you would like to publish your Deck as private, so that only you may view & interact with it,
            click
            the Lock button on the top-right corner of the Deck in the <b>Deck Master</b>. You may change the Deck's
            privacy status at any time by clicking this button anywhere else on PalWeb where your Deck appears.</p>

        <h2>Study Mode</h2>
        <p>When entering the Deck Master in this mode, on the <b>Select</b> page you will see an array of all your
            Pinned Decks & a window that displays a preview of your Score history for a Deck. Click on any of the
            available Decks to select it, then click the "Select Deck" button at the bottom of the Score history window
            to proceed to the <b>Study</b> page.</p>
        <p>In <b>Practice</b> mode, you will be presented with the Terms in the Deck as a carousel of Cards that you
            can swipe through. You can also use the keyboard to control the application. Use the Options panel to change
            how you view the Cards. Experiment with the different options to find the settings most suitable for you!
            (Refer to the information dialog in the Options panel for more information about each setting & a full list
            of keyboard controls.)
        </p>
    </div>
    <div class="wiki-content-block" id="record-wizard">
        <h1>Record Wizard</h1>
        <p>The <b>Record Wizard</b> is a tool to record yourself speaking Arabic. Its primary purpose is as a means to
            source audio samples of the many possible Pronunciations of the Terms in the PalWeb Dictionary; this is for
            documentation purposes, but is also practical for learners of Arabic dialects to have something to listen
            to,
            practice with & emulate.</p>
        <p>But the <b>Record Wizard</b> is not necessarily only for native speakers to use. If you speak Palestinian
            Arabic
            well, or even if you just want to practice, you are welcome to use the Record Wizard too. All Audio files
            are
            associated with a Speaker whose Fluency level is indicated everywhere that they appear, so it will always be
            clear which Audios belong to native speakers as opposed to other learners of Arabic.</p>

        <p>On opening the <b>Record Wizard</b>, your browser may ask you for permission to use of your mic; you must
            allow
            this to proceed. The <b>Record Wizard</b> is divided into four areas:</p>

        <h2>Speaker</h2>
        <p>On the <b>Speaker</b> page, you will create or adjust your Speaker profile & test your mic.</p>
        <p>On the left-hand panel, fill out the form & create your Speaker profile if you haven't already. Refer to the
            information dialog at the top-right corner of the Wizard for more information on how to appropriately fill
            out
            all the fields. With the exception of your Dialect (see below), you may adjust these values at any time, but
            you
            will likely never need to — aside from Fluency, if you're a learner.</p>
        <p></p>
        <p>On the bottom of the right-hand panel, you will see a "Waiting" status. Click the Play icon to start a
            recording
            test. When you see a "Recording" status, speak into the mic. If sound is detected, then once you finish
            speaking
            a "Listening" status will be shown & the Wizard will automatically play back your test recording. If
            everything
            is in order, use the navigation bar to move to the next step.</p>

        <h3>Dialect</h3>
        <p>Your Dialect is the variety of Palestinian Arabic that you speak, or that you intend to represent in your
            Audios.
            PalWeb is an ongoing research project, so the list of Dialects is provisional & will grow & be adjusted as
            more
            Speakers from different backgrounds record themselves in a way that pushes against the rigidity of the
            existing
            list. However, this must be done carefully: <b>Once your Speaker profile is created, your Dialect can only
                be
                changed by the site administrator.</b> This is to ensure the integrity of the data & prevent misuse of
            the
            application, intentional or accidental.</p>
        <p>If what you consider to be your Dialect does not appear on the list, simply choose the closest one. You may
            see
            discrepancies between the items presented to you & the way you pronounce them; this is absolutely normal.
            Just
            record the items in the way that comes naturally to you. You are invited to reach out to the site
            administrator
            about this, or they may notice & get in touch with you to suggest a change to your Dialect.</p>

        <h2>Queue</h2>
        <p>On the <b>Queue</b> page, you will queue up a list of Terms to record. Refer to the information dialog at the
            top-right corner of the Wizard for more information on how to add items to the Queue. You may return to the
            Queue page at any time to adjust the list & add more items within the same session.</p>

        <h2>Record</h2>
        <p>On the <b>Record</b> page, you will record the items in your Queue & upload the recordings to the cloud
            server
            (this automatically publishes them on PalWeb).</p>
        <p>On the left-hand panel you will see your Queue. A dark-blue background on an item indicates that it is the
            currently selected item. On the right-hand panel, the currently selected item will be displayed in Arabic
            with
            its Latin-script transcription. You can change the currently selected item by selecting it directly from the
            left-hand panel, or by using the arrow buttons around the item in the right-hand panel.</p>
        <p>On the bottom of the right-hand panel, there are sections labeled "Record" & "Upload". The Record section
            includes a button that toggles the recorder on & off, a volume meter that will display the volume of the
            received audio & a counter that displays how many of the queued items have been recorded. The Upload section
            includes a button that uploads your stashed recordings, a progress bar for the upload process & a counter
            that
            displays how many of the stashed recordings have been uploaded.</p>
        <p>When you are ready to start recording, click the Record button. You will see the volume meter change from
            yellow
            to purple to indicate that the recorder is listening for your voice. Pronounce the item. The Wizard will
            automatically clip the recording when you're done speaking & select the next item in the Queue. You may
            continue
            recording items this way & the Queue panel will automatically scroll with you.</p>
        <p>Once an item is recorded, it is <b>stashed</b> temporarily in local storage, becoming yellow in the Queue.
            When
            an item with a stashed recording is selected again, the Record panel will display a Play button to preview
            the
            recording & a Delete button to discard it. Keep in mind that stashed recordings are not yet on the cloud
            server,
            nor are they visible on PalWeb until you upload them; they only exist in the current recording session, so
            they
            will be discarded if you exit the Wizard or if you return to the <b>Queue</b> page & remove the item or
            flush
            the Queue.</p>
        <p>You may upload your stashed recordings at any time in the Upload panel, beneath the Record panel. You don't
            have
            to record all the items in the Queue before doing so, but keep in mind that the Wizard will process all the
            stashed recordings at once; you cannot select individual stashed recordings to upload, so make sure to
            verify
            the quality of all your stashed items before initiating the upload process. Notice that while the Record
            counter
            indicates how many of the queued items have been stashed, the Upload counter indicates how many of the
            latter
            have been uploaded. Once the upload process is finished, the progress bar will be filled, but it will fall
            below
            100% again if additional stashed recordings are created.</p>
        <p>Once you've uploaded at least one item, you may proceed to the <b>Check</b> page at any time to verify your
            uploads. You may always return from the <b>Check</b> page to the <b>Record</b> page to continue recording.
        </p>

        <h2>Check</h2>
        <p>On the <b>Check</b> page, you can view a list of your created Audios & preview them. Unlike stashed
            recordings,
            the Audios listed on this page are on the cloud server & have been published to PalWeb. Still, it is always
            good
            practice to double-check your new Audios, in case anything was uploaded by accident. Clicking the Delete
            button
            for an Audio on the <b>Check</b> page will delete it from the cloud server & the database; it is the same as
            deleting an Audio from any other page on PalWeb.</p>
        <p>The Info box at the top of the Wizard includes a link to your Speaker profile so that you can view your new
            files on PalWeb without exiting the Wizard, in case you would like to continue the recording session.</p>

        <AppTip>
            <p>By using the PalWeb Record Wizard, you agree to the publication and use of your recordings under the
                <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons CC BY 4.0</a>
                license, which allows anyone to share, use, and adapt your recordings, even for commercial purposes,
                provided they give proper attribution. PalWeb will only use your recordings for non-commercial
                educational purposes. You will have full control over your recordings & may remove them from the site at
                any time. However, once your recordings are published, you cannot revoke this license, meaning you
                cannot withdraw or restrict others' use of your recordings, even if you remove them from the site.</p>
            <p>Your recordings may also be shared on third-party platforms like Wikimedia Commons to increase
                accessibility
                and public utility. These platforms operate independently of PalWeb and have their own removal and
                take-down
                policies; we cannot guarantee the removal of recordings from third-party platforms once they are
                uploaded.
                For Wikimedia's terms & policies, please visit <a
                    href="https://commons.wikimedia.org/wiki/Commons:Licensing" target="_blank">Wikimedia Commons
                    Licensing.</a></p>
        </AppTip>
    </div>

</template>
