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
        <AppTip>
            <p>PalWeb is looking for a regular research assistant that can help with any or all of the following: use
                our documentation tools to create entries for the Dictionary & transcribe Dialogs; seek out native
                speakers to record Audios for the site; write or co-write for the Wiki or commission articles for it.
                Get in touch with <b>adrian@palweb.app</b> if you are interested.
            </p>
        </AppTip>

        <h1>Contribute to PalWeb</h1>
        <p>PalWeb is a multidisciplinary project that brings together learners, educators, researchers, speakers — &
            also developers! One universal way to help is by using the <b>Send Feedback</b> form in the Sidebar to
            suggest improvements & request Terms that you notice are missing from the Dictionary. But if you want to
            contribute more actively, here's what you can do to build the project:</p>

        <h2>for Learners & Educators</h2>
        <p>You're already helping to build just by using the site & spreading the word! Other practical ways to
            contribute include
            <Link :href="route('deck-master.index')">Building Decks</Link>
            for yourself & others to use, enriching the Deck Library.
        </p>

        <h2>for Researchers & Speakers</h2>
        <p>The PalWeb Wiki, in addition to providing information about the application, offers a descriptive grammar of
            Palestinian Spoken Arabic. PalWeb is looking for contributors with knowledge of linguistics & of Spoken
            Arabic to write or co-write sections of the Wiki.</p>
        <p>PalWeb is also looking for people who would be willing to volunteer to record audio samples of terms &
            sentences for the Dictionary, so please get in touch (<b>adrian@palweb.app</b>) if that sounds like you.
        </p>

        <h2>for Developers</h2>
        <p>PalWeb is a single-page application (SPA) built on Laravel for the backend & Vue for the frontend, brought
            together with Inertia. If you're a developer with knowledge of Javascript & PHP, especially with experience
            with the Vue & Laravel frameworks, please email <b>adrian@palweb.app</b> if you'd like to contribute to the
            project. PalWeb is an open-source project, so you can get started right away by cloning the public
            repository on <a href="https://github.com/PalWeb-Online/PalWeb" target="_blank">GitHub</a>.
        </p>
    </div>
</template>

