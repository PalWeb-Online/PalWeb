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
        <h2>PalWeb 2.2: Jerusalem</h2>
        <p>Welcome to <b>PalWeb 2.2: Jerusalem</b>. As promised, this build introduces the meat of the Academy section:
            the Lessons. By this I mean that I have wired up everything & have developed in-app tooling to create
            Lessons & Activities with flexibility & speed without shipping any new code. Since I've only just deployed
            those features, it's only now that I can begin adding Lessons to the course.</p>
        <p>My original intention was to have Unit 1 available at launch, but I wasn't able to meet that mark. Still, we
            don't have to wait for big deployments anymore to see this content added to the site, as now that these
            features are available I will be adding new Lessons every week. At the time of writing, no Lessons are
            available, but by the time you read this there will probably be several.</p>
        <p>My official objective is to publish one Unit per month until the main course is completed, as I have other
            tasks detailed below; however, I may advance quicker than that based on how the work proceeds. I foresee the
            main course containing 9 Units, each of which has 9 Lessons. Hence, the main course should be completed by
            the end of September at the latest — & most likely sooner.</p>
        <p>Each Lesson contains a <b>Deck</b> of 24 Terms, a set of 3 <b>Skills</b> to learn that are tested in an
            <b>Activity</b>, & a <b>Dialog</b>. Considering a planned 81 Lessons in total, the PalWeb Academy should
            help you reach a solid intermediate
            level in Spoken Arabic, with a vocabulary of nearly 2,000 Terms. When you first
            unlock the Lesson, only the Deck is available for study; you must Quiz it 3 times obtaining a Score of 100%
            to proceed to the Skills section, which makes use of the vocabulary you've learned. The Skills introduce
            bite-sized concepts focused on some practical usage context, so that you always come away with the sense
            that you're able to express yourself in a new way or navigate real-life situations that you previously
            couldn't. After digesting that information, you enter the Activity, where interactive exercises will have
            you applying the Skills to prepare you to put them into practice. Once you can ace the Activity with a Score
            of 100%, you will unlock the Dialog, which integrates everything you've learned in a realistic context. But
            the idea isn't to simply read the Dialog: toggle off parts of it to turn the Dialog into a template to
            practice with someone else. Do it enough & you'll find that the phrases will roll of the tongue when you
            find yourself in similar situations in real life!</p>
        <p>While <b>PalWeb 2.2</b> inaugurates a long-awaited shift toward content creation rather than coding, there is
            still a series of important features that I must add to make the application even more robust for language
            learning & documentation; these will continue to be deployed at a rate of once every three months. Below you
            will find a summary of those upcoming features & the justification for them.</p>
        <p>One more thing: <b>PalWeb 2.2</b> technically restores localization to the application to make it available
            in other languages, namely Spanish & Arabic, but the feature is not public yet. Localization used to be
            partially supported, but the translations were a pain to maintain when the site was rapidly evolving &
            changing in terms of frontend rendering, so I gave up on it. Arabic localization will still be a pain due to
            the fact that I need to support mirroring certain design elements. It should be noted that in any case only
            the UI & certain important pages would be localized, not the content of the site itself (e.g. the Wiki,
            Glosses in the Dictionary, etc.). Because it's quite a hassle with quite limited returns, this has always
            been a low-priority task. Still, I will be passively adding localization data over time & once most of the
            key areas are accounted for I will enable locale switching.</p>

        <h2>Roadmap</h2>
        <p>Refer to the
            <Link :href="route('wiki.show', 'about')">About</Link>
            page for some history about the project that puts these milestones into context.
        </p>

        <h3>(04/2026) PalWeb 2.3: SRS</h3>
        <ul>
            <li>Units 1-3</li>
        </ul>
        <p><b>PalWeb 2.3</b> will introduce spaced-repetition study (<b>SRS</b>) for Decks & the Dictionary. By powering
            the system that makes flashcard applications like Anki great with PalWeb's rich database, you'll be able to
            track your progress through the entire Palestinian Arabic lexicon & see your mastery level for every Term as
            you navigate the Dictionary. I'm very excited about this game-changing feature, but I'd like to keep the
            details a surprise.</p>

        <h3>(07/2026) PalWeb 2.4: SoundBooth & WordLogger</h3>
        <ul>
            <li>Units 4-6</li>
        </ul>
        <p>At present, only Term Pronunciations may be recorded. I will set up the ability to record pronunciation
            samples for other types of models, like Inflections & Sentences. I hope to also be able to enable recording
            entire Dialogs between multiple Speakers. As a prerequisite for this, though, I will have to fix certain
            aspects of how Dialects are currently tagged, which is fraught for a variety of reasons.</p>
        <p>I'm also aware that more content needs to be added to the Dictionary, but this must be done in an organized &
            intelligent way. My intention is to feed the machine-readable data provided by the
            <a href="https://sites.google.com/nyu.edu/palestine-lexicon/home" target="_blank">Maknuune</a>
            lexicon of Palestinian Arabic to PalWeb in order to programmatically create thousands of new entries; the
            challenge
            will be reviewing those entries, adapting them & fleshing them out for PalWeb, since its database has a
            complex relational structure with many required data points that the lexicon does not include. Yet while the
            current structure of the Dictionary has proved to be quite flexible & robust, there is no good way for
            administrators to manage the workflow, so I have to add additional content-management features to the
            WordLogger.</p>
        <p>I don't think any of this will be particularly difficult — I just need to get to it — but I'm spreading out
            the work over three months in order to maintain a steady rate of educational content creation. In any case,
            if all goes well, starting in July we will start to see a lot of new Terms & Audios on PalWeb.</p>

        <h3>(10/2026) PalWeb 2.5: SpeechMaker</h3>
        <ul>
            <li>Units 7-9</li>
        </ul>
        <p>Likewise, there are currently only a couple hundred Sentences in the Corpus — that's not a corpus. Again,
            adding Sentences individually — while very easy using the SpeechMaker — is not a scalable way to add
            thousands upon thousands of entries. My intention is to upload the
            <a href="https://huggingface.co/datasets/guymorlan/levanti" target="_blank">Levanti</a>
            dataset to the application, but that means figuring out how to transform mere text strings into blocks of
            Terms in the Dictionary & create a more robust system for reviewing & managing Sentences in the
            SpeechMaker. I can then explore doing the same with other datasets.</p>

        <h3>(01/2027) PalWeb 2.6: Classrooms</h3>
        <p>By this point, the main course in the Academy should be complete & the Dictionary & Corpus will boast many
            thousands of entries, so I will proceed to work on features for educators, namely the ability to create
            Classrooms of Students, monitor their Scores & possibly create & assign custom Activities; this is pretty
            self-explanatory. While this is a relatively simple task, this stage is still a year away & new
            possibilities & tasks are sure to arise before then. One other aspect the site could benefit from
            developing, for instance, is the community-building & networking aspect of the application, which remains
            pretty superficial at this time.</p>

        <h3>(07/2027) PalWeb 3.0: the PalWeb App</h3>
        <p>I previously intended to optimize PalWeb as a progressive web application (PWA) to launch the <b>PalWeb
            App</b> on the Android & iOS app stores in July 2026, but I'm delaying this to July 2027. As you can see,
            there are many other high-priority tasks before me & I think it makes more sense to take this on once the
            application has reached a more mature state; it would be the final capstone of what is intended to be a
            five-year project that began in Summer 2022.</p>
        <p>Based on the state of the project at that time, I will determine the path forward. If there is interest in
            the project, I am still interested in creating a <b>LangWeb</b> framework of learning & documentation tools
            on the basis of what I've built for PalWeb.</p>
    </div>

    <div class="wiki-content-block">
        <h1>Version History</h1>
        <ul>
            <li>01/01/2026 — PalWeb v2.2: Jerusalem <b>(Current)</b></li>
            <li>01/10/2025 — PalWeb v2.1: Nabatean</li>
            <li>01/07/2025 — PalWeb v2.0: Watermelon</li>
            <li>01/04/2025 — PalWeb v2.0 (BETA)</li>
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

    <div class="wiki-content-block">
        <h1 style="text-transform: none">Welcome to PalWeb 2.0</h1>
        <p>PalWeb 2.0 transforms the site into a single-page application (SPA); this leap forward, which had been the
            focus of the previous six-month period, was necessary to establish the basis on which to build the
            Academy.</p>
        <p>I launched the Beta in April for testing, knowing that there were still various issues with the application,
            especially — but not only — in terms of visual performance, accessibility & consistency on mobile devices.
            While I had announced some additional Academy features for the stable build, I decided that it was crucial
            to iron out all these outstanding issues so that the final product be as polished as possible. It has
            happened too many times that while trying to build new features, the cracks in the work that I had
            considered "done" began to show, forcing me to take time to attend to them & therefore run behind schedule.
            Moreover, having built a new system for creating interactive Dialogs, I had to recreate the existing ones
            from scratch; this took several weeks of painstaking work that involved adding many new Terms to the
            Dictionary, bringing us to 3,000.</p>
        <p>Thus, the PalWeb 2.0 stable build is a practically finalized form of the core application upon which
            additional features will be built; however, it doesn't include the aforementioned Academy features yet.
            Instead, I've developed new milestones that will ensure a sufficient level of quality while also delivering
            the features you've been waiting for within a reasonable timeframe. Instead of hurriedly deploying ambitious
            packages of features that are "mostly" finished & that would be ironed out as I go along, I'm going to
            deploy individual features on a monthly basis over the course of a year. Keep in mind that this includes
            building the functionality, designing it for good performance on a variety of devices, & writing the content
            itself. What's good is that the highly stable state of PalWeb 2.0 at this time will allow me to focus
            exclusively on what's new & not get bogged down by issues in areas previously considered complete.</p>
        <p>I won't go over the new features of PalWeb 2.0 again, which were covered in the 2.0 Beta Release Notes.
            Instead, check the
            <Link :href="route('wiki.show', 'user-guide')">User Guide</Link>
            for more information on
            how to use the various features of the site. Remember, if you find anything that doesn't work or look as
            expected or have any other suggestions for improvement, write to me using the <b>Send Feedback</b> button
            in the Sidebar (you must be logged in).
        </p>

        <h2>PalWeb 2.1: Nabatean</h2>
        <p>Welcome to <b>PalWeb 2.1: Nabatean</b>. As outlined in the PalWeb 2.0 Roadmap (see below), the
            latter half of 2025 would be dedicated to building the Academy section of the site. During this first stage
            of that process, I've been working on the ability to generate Quizzes from Decks & on saving Scores for
            Quizzes.</p>
        <p>I originally launched the Quizzer in August as a portal within the Academy. I've since moved it to the Deck
            Master, making this the single unified portal for reviewing Decks as flashcards & Quizzing them. I've also
            added a new Quiz type, bringing the total to three: multiple-choice Gloss Quizzes, short-answer Inflection
            Quizzes & fill-in-the-blank Sentence Quizzes. The Quizzer generates each Quiz on the spot using the Terms in
            the Deck, so each Quiz is unique. Drill your vocabulary to your heart's content!</p>
        <p>But what's the point of Quizzes if you can't see your progress? After doing the Quiz, you can now save your
            Score, which is visible in the Scores section of the Academy, as well as in the Deck Master. See your
            Score history for any Deck as an interactive line graph, with quick statistics that tell you how your
            knowledge has evolved over time. You can view any previously-taken Quiz to see the exact questions &
            responses provided, so you can review what you got wrong & adjust your Decks as needed.</p>
        <p>I will now be working on the Lessons & the Activities, the meat of the Academy section. Specifically, I will
            set up the Lessons architecture in October, create a system for building custom Activities in November, &
            lay out all of the Unit 1 content in December. It doesn't really make sense to publish these features
            incrementally. Rather, I'm aiming to deploy the entire built Academy section with Unit 1 available on
            January 1, as <b>PalWeb 2.2</b>; that build will take us into next summer, as during the entire first half
            of 2026 I will simply be rolling out new Units every month.</p>
    </div>
</template>
