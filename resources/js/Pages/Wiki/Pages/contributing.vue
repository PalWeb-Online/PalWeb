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
        <h1>for Developers</h1>
        <p>PalWeb is now open source! Check out the public repository on GitHub!</p>
        <p>PalWeb is built on Laravel & primarily uses the Blade templating engine to render views, but I will probably
            have
            to increasingly make use of Vue to achieve the interactivity necessary to build the next stages of the
            project.
            If you're a developer with knowledge of Javascript & PHP, especially in the use of the Vue & Laravel
            frameworks,
            please get in touch (<b>adrian@abdulbaha.xyz</b>) if you'd like to contribute to the project. You can get
            started right away by cloning the public repository on GitHub.
        </p>
        <p>Another thing I haven't investigated too much but will probably need help with is the porting of the
            <a href="https://github.com/lingua-libre/RecordWizard/tree/master" target="_blank">RecordWizard</a>
            MediaWiki
            extension, created for Lingua Libre, the site through which audio recordings are currently produced.
            Apparently
            it's mostly Javascript, so I don't see why it couldn't be adapted to a non-MediaWiki site.</p>
        <p>Anyway, here's what's on the horizon for PalWeb in the near future:</p>
        <ul>
            <li>the Activities within the PalWeb Academy</li>
            <li>User Progress tracking</li>
        </ul>
    </div>

    <div class="wiki-content-block">
        <h1>Everyone Else</h1>
        <p>I'm primarily looking for people who would be willing to volunteer to record audio samples of terms &
            sentences for the Dictionary, so please get in touch (<b>adrian@abdulbaha.xyz</b>) if that sounds like you.
        </p>
        <p>Even if you're not a developer or an Arabic speaker, there's plenty you can do to contribute to the project.
            Just by using the site, you help to build it!
            <Link :href="route('deck-master.index')">Building
                Decks
            </Link>
            for yourself & others to use & requesting Terms that you notice are missing from the Dictionary via the <b>Send
                Feedback</b> form in the Sidebar are two
            practical ways to contribute. Most
            importantly, though, don't forget to spread the word!
        </p>
    </div>
</template>

