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
        <p>Welcome to <b>PalWeb</b>, the Web of Palestinian Arabic. PalWeb is the online hub for the study & research of
            the Palestinian dialects of Spoken Arabic. If you're ready to start learning or documenting Spoken Arabic,
            you've come to the right place! Check the
            <Link :href="route('wiki.show', 'user-guide')">User Guide</Link>
            for more information on how to use PalWeb. If you're a researcher or a native speaker interested in
            documentation, or a developer interested in contributing to the source code, check the
            <Link :href="route('wiki.show', 'contributing')">Contributing</Link>
            page.
        </p>
        <h2>What’s PalWeb?</h2>
        <p><b>PalWeb</b> stands for multiple things. At first glance, it's short for "<b>Pal</b>estinian Arabic
            <b>Web</b>" — but a "<b>Web</b>" of what, exactly? In cases like these, "web" is usually short for "website"
            — but why are websites called that way? Because they are interconnected networks of pages or sites, all
            linked together like a web! But <b>PalWeb</b> is not just a web of pages. It's a web of information, powered
            by a database: words that link to other words, building blocks of language chained into sentences to express
            information; in all of their possible configurations, they encapsulate the totality of human knowledge.
            Language is a web of information, of meaning. <b>PalWeb</b> is the Web of Palestinian Arabic.</p>
        <p>As for "<b>Pal</b>", in English it means "friend", while in Arabic a "web" & a "network" are the same thing:
            <b>شبكة (šabake)</b>. In other words, <b>PalWeb</b> is a network of different types of people brought
            together on one platform, offering the means to create & share learning materials, while its Discord server
            provides the opportunity for Spoken Arabic speakers & learners to interact.</p>


        <h2>Why PalWeb?</h2>
        <p><b>PalWeb</b> is the Web of Palestinian Arabic. But many others exist. Every language is a Web in the
            tapestry of the intangible heritage of our species. But language does not exist in a vacuum devoid of users.
            Many have been lost with them, often through violence. Often, language itself is the target of violence.
            Palestine is engaged in a struggle for existence, but it is neither the first nor the last of its type. It's
            not even the only one happening right now. It's not even the only community whose existence is contradictory
            to Zionism right now. Armenians, for one. But also Jewish minorities that have been absorbed by the Zionist
            entity, their identities sanitized: Ladino is endangered. Yiddish is looked down upon as a language of
            exile. Language is a lens that magnifies the genocidal, supremacist logic of Zionist nation-building. When
            speaking is an act of resistance, learning is a conscription to the cause; documentation is historiography.
        </p>

        <h2>Origins of PalWeb</h2>
        <p><b>PalWeb</b> traces its origins back to May 2020 with the publication of the first video on the <b>Learn
            Palestinian Arabic</b> channel (now <a href="https://www.youtube.com/@PalWebTV" target="_blank">PalWeb
            TV</a>) on YouTube. During the first two years or so of that project, static learning resources were posted
            to <a href="https://www.patreon.com/palweb" target="_blank">Patreon</a>. In November 2021, I had the idea of
            creating a site that could host all the resources I had created & adapt the videos I had posted to YouTube
            in a more interactive format. I didn't know anything about web development, so the site was extremely basic
            at
            first. In May 2022, though, I started learning web development to materialize my vision of an interactive
            portal for learning & documenting Palestinian Arabic that could overcome the limitations of traditional
            materials & other existing resources. As a result of this work, the <b>Learn Palestinian Arabic</b> site was
            officially launched in May 2023. Over the course of the following year, I further defined the project's
            scope & long-term vision, culminating in the launch of <b>PalWeb 1.0</b> in July 2024, accompanied by a
            total overhaul to the site's design, which I've iterated over for <b>PalWeb 2.0</b>.</p>

        <h2>Future of PalWeb</h2>
        <p>Now three years into the project, PalWeb has become — more than I ever expected at the start — a complex
            multidisciplinary project, involving frontend & backend development, artistic work & UX/UI design,
            linguistic research for the Dictionary & the Wiki, let alone the pedagogical offerings of the site. Even so,
            PalWeb is to this day run by a single person; I have had to balance all of this work simultaneously, with
            mixed results at times. There are two main reasons for this:</p>
        <p>Firstly, from Summer 2022 to Summer 2025, I taught myself everything I know about web development from
            scratch. I spent about half of that time becoming competent at PHP & the second half becoming competent at
            Javascript, in addition to learning many lessons pertaining to web design, server management, etc. All of
            this came together for <b>PalWeb 2.0</b> — a single-page application (SPA) that brings these two languages
            together
            & thus marks a huge milestone for the project & for myself as a person. While we are never really done
            learning, building the application from here on out will no longer be the uphill battle of learning by
            doing. While that struggle was absolutely necessary, it meant a relatively low productivity of work over a
            long period of time. I essentially had to dedicate 100% of my time & energy into coding & learning with
            little to no time dedicated to developing the project more broadly, like doing outreach & bringing more
            people on board. After all, how could I lead a team if I myself hardly knew what I was doing?</p>
        <p>Secondly & on a related note, I have done virtually no promotion of the project. Most people who know me from
            the YouTube videos I was making prior to starting PalWeb don't seem to even know the project exists. In
            part, it didn't make sense to promote an unfinished project. But, as a result, I have been financially
            constrained during this entire period & to this day I simply cannot afford to pay to bring anyone else on
            board & build a team — not even a little bit.</p>
        <p>Still, <b>PalWeb 2.0</b> marks a turning point for the project. While PalWeb <b>PalWeb 1.0</b> introduced the
            site's
            new scope &
            identity, <b>PalWeb 2.0</b> finalizes the technical foundations of the project. Now my task is to build upon
            this
            foundation to add the final Academy features & continue to flesh out the site content itself. At the same
            time, I'm now much faster at what I do, so I have more time to develop the project more broadly. I'm also
            better at estimating the time needed to build new features & reach certain milestones, since I already have
            a high-level idea of how I will approach the work. Therefore, I have more or less planned out the next two
            years of what will ultimately be a five-year project. Over the next year, I will be rolling out new features
            incrementally, giving myself time both for coding & for outreach. (See the
            <Link :href="route('wiki.show', 'release-notes')">Release Notes</Link>
            for a timeline.)
        </p>
        <p>Finally, PalWeb is the pilot project of <b>LangWeb</b>: a modern, open-source modular web application
            for down-to-Earth, user-friendly language learning & documentation. <b>LangWeb</b> will be the outcome of
            PalWeb reaching its final stage of development; at that stage, the Arabic vestments of the
            application may be removed, leaving a modular slate adaptable to any other language. I imagine I will
            dedicate myself to this after closing out the fifth year of PalWeb in 2027.</p>

        <p>If you'd like to contribute to the project, refer to the
            <Link :href="route('wiki.show', 'contributing')">Contributing</Link>
            page or consider making a one-time donation on
            <a href="https://www.ko-fi.com/palweb" target="_blank">Ko-fi</a>
            or taking out a
            <Link :href="route('subscription.index')">Subscription</Link>
            to the site.
        </p>
    </div>
    <div class="wiki-content-block">
        <h1>have We Met?</h1>
        <img src="/img/adrian.jpeg"
             style="max-width: 30%; float: right; margin-inline-start: 2.4rem; border-radius: 1.6rem" alt="R. Adrian"/>
        <p>My name is <b>R. Adrian</b>: project manager, website developer, curriculum designer, dictionary writer &
            creator of PalWeb. I'm Colombian, not Palestinian; I began learning Arabic from scratch in university &
            developed those skills while working on & off in Palestine over the years. I have a BA in Anthropology from
            the University of Notre Dame & an MA in Middle Eastern Studies (Palestine Studies) from SOAS University of
            London.</p>
        <p>If you'd like to get in touch, you can email me at <b>adrian@palweb.app</b> or send a message to
            <b>rfi.adrian</b> on Discord.</p>
        <p>Check out
            <Link :href="route('users.show', 'permanent.intifada')">my PalWeb account</Link>
            — or
            <Link :href="route('users.show', 'palweb.app')">the official
                PalWeb account
            </Link>
            — to see some of the stuff I've made for the site.
        </p>
    </div>
</template>
