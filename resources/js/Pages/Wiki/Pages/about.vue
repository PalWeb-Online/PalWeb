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
        <h1 style="text-transform: none">Welcome to PalWeb!</h1>
        <p>Welcome to <b>PalWeb</b>, the Web of Palestinian Arabic. PalWeb is the online hub for the study & research of the
            Palestinian dialects of Spoken Arabic. If you're ready to start learning Spoken Arabic, you've come to the right
            place!</p>
        <h2>What’s PalWeb?</h2>
        <p><b>PalWeb</b> stands for multiple things. At first glance, it's short for "<b>Pal</b>estinian Arabic <b>Web</b>"
            — but a "<b>Web</b>" of what, exactly? In cases like these, "web" is usually short for "website" — but why are
            websites called that way? Because they are interconnected networks of pages or sites, all linked together like a
            web!</p>
        <p><b>PalWeb</b> is a web, yes, but not just a web of pages. It's a web of information, powered by a database: words
            that link to other words, the building blocks of language that are chained into sentences to express
            information; in all of their possible configurations, they encapsulate the totality of human knowledge.</p>
        <p>Language is a web of information, of meaning. <b>PalWeb</b> is the Web of Palestinian Arabic.</p>
        <p>But that's not the whole story. One of the most important aspects of language learning is community. "<b>Pal</b>"
            in English means "friend" — & in Arabic, a "web" & a "network" are the same thing: <b>شبكة (šabake)</b>. <b>PalWeb</b>
            is a network of language-learners for a new generation, offering the means to create & share learning materials,
            while its Discord server provides the opportunity for Spoken Arabic speakers & learners to interact.</p>


        <h2>Why PalWeb? What’s Next?</h2>
        <p>Another Web, of course, is possible; thousands
            exist, possibly one for every human being on the planet. Each one is a thread in the tapestry of the intangible
            heritage of our species. Admire its artistry. Study its intricacy. We can do both.</p>
        <p>But language does not exist in a vacuum. In a vacuum devoid of speakers, it does not exist. Many have been lost
            this way, often through violence. Often, language itself is the target of violence. When speaking is an act of
            resistance, learning is a conscription to the cause. Documentation is a form of history.</p>
        <p>Palestine is engaged in a struggle for existence, but it is neither the first nor the last of its type. It's not
            even the only one happening right now. It's not even the only community whose existence is contradictory to
            Zionism right now. Armenians, for one. But also Jewish minorities that have been absorbed by the Zionist entity,
            their identities sanitized. Ladino is endangered. Yiddish is looked down upon as a language of exile. Language
            is a lens that magnifies the genocidal, supremacist logic of Zionist nation-building.</p>
        <p><b>PalWeb</b> is the pilot project of <b>LangWeb</b>: a modern, open-source modular web application for
            down-to-Earth, user-friendly language documentation & education. <b>LangWeb</b> will be the outcome of
            <b>PalWeb</b> reaching its final stage of development. That’s why, first, several features must be added to
            <b>PalWeb</b> before the Arabic vestments of the application may be removed, leaving a modular slate adaptable
            to any other language: a Record Wizard to seamlessly create audio samples; more robust administrator options,
            roles & permissions; & many other features that will emerge organically over time.</p>
        <p>Building PalWeb is, more than I expected, a multidisciplinary project, involving design, coding, pedagogy &
            linguistics. After the big push to launch <b>PalWeb</b> on 1 July 2024 — involving a total overhaul to the
            design — I can say this dimension of the project has been essentially completed. In the next period, I will have
            to balance adding terms to the Dictionary, filling out the Wiki & adding content to the Academy with the broader
            task of building the site itself & therefore building toward <b>LangWeb</b>. It’s not very feasible for one
            person to do it all, but I try my best. If you would like to help, read the <Link
                :href="route('wiki.show', 'contributing')">Contributing</Link> page, or consider making a one-time
            donation on <a
                href="https://www.ko-fi.com/palweb" target="_blank" style="font-weight: 700">Ko-fi</a>
            or taking out a <Link :href="route('subscription.index')" style="font-weight: 700">Subscription</Link>
            to the site.</p>
        <p>Still, the coming period is looking toward <b>PalWeb 2.0</b> in July 2025. Just as <b>PalWeb 1.0</b> introduced &
            finalized the site’s design, <b>PalWeb 2.0</b> should finalize the coding side of the project. In other words,
            the stage will be set to normalize the application into <b>LangWeb</b> — & I already have an idea of what
            language is next. Of course, that project will be carried out by someone else. I will focus on using the tools
            I’ve created to finish fleshing out the PalWeb Dictionary, the Academy, etc.</p>
    </div>
    <div class="wiki-content-block">
        <h1>have We Met?</h1>
        <img src="/img/adrian.jpeg" style="max-width: 30%; float: right; margin-inline-start: 2.4rem; border-radius: 1.6rem" alt="R. Adrian"/>
        <p>My name is <b>R. Adrian</b>: project manager, website developer, curriculum designer, dictionary writer & creator
            of PalWeb. I'm Colombian, not Palestinian; I began learning Arabic from scratch in university & developed those
            skills while working on & off in Palestine over the years. I have a BA in Anthropology & an MA in Middle Eastern
            Studies (Palestine Studies). You can ask me in private where I studied, as I'm not going to give public credit
            to those institutions.</p>
        <p><b>PalWeb</b> traces its origins back to May 2020 with the publication of the first video on the <b>Learn
            Palestinian Arabic</b> channel (now <a
            href="https://www.youtube.com/@PalWebTV" target="_blank">PalWeb
            TV</a>) on YouTube. During the first two years or so of the project, static learning resources were being
            posted on <a href="https://www.patreon.com/palweb" target="_blank">Patreon</a>. In November 2021, I had the idea
            of creating a website that could host all the resources I had created & adapt the videos I had posted to YouTube
            in a more interactive format. I didn't know anything about web development, so the site was extremely basic at
            first. However, in May 2022 I learned PHP (Laravel) & Javascript (Vue) to materialize my vision of an
            interactive portal for learning Palestinian Arabic that could overcome the limitations of traditional materials;
            the result was the <b>Learn Palestinian Arabic</b> site, officially launched in May 2023. In 2024, <b>PalWeb</b>
            as such was born.</p>
        <p>If you'd like to get in touch, you can email me at <b>adrian@abdulbaha.xyz</b> or send a message to
            <b>rfi.adrian</b> on Discord. Or, if you just want to confirm that I'm a Real Person, you can follow <a
                href="https://twitter.com/rafi2_ab" target="_blank">my very unprofessional Twitter.</a>
        </p>
        <p>Check out <Link :href="route('users.show', 1)">my PalWeb account</Link> — or <Link :href="route('users.show', 2)">the official
            PalWeb account</Link> — to see some of the stuff I've made for the site.</p>
    </div>
</template>
