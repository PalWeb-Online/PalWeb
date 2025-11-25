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
        <h2 style="text-transform: none">PalWeb 2.1: Nabatean</h2>
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

        <h2>Roadmap</h2>
        <p>Refer to the
            <Link :href="route('wiki.show', 'about')">About</Link>
            page for some history about the project
            that puts these milestones into context.
        </p>
        <ul>
            <li>October 2025</li>
            <ul>
                <li>Lessons: Create Lesson models in the backend. Create an interface for navigating through &
                    visualizing Lessons. Set up progression & unlock system for Lessons & individual Lesson content.
                </li>
            </ul>
            <li>November 2025</li>
            <ul>
                <li>Lessons: Design Activities & Exercises in the backend & frontend. Create Unit 1
                    Activities.
                </li>
            </ul>
            <li>December 2025</li>
            <ul>
                <li>Lessons: Roll out Unit 1.</li>
            </ul>
            <li>January 2026</li>
            <ul>
                <li>Deploy <b>PalWeb 2.2</b>.</li>
                <li>Lessons: Roll out Unit 2.</li>
            </ul>
            <li>February 2026</li>
            <ul>
                <li>Lessons: Roll out Unit 3.</li>
            </ul>
            <li>March 2026</li>
            <ul>
                <li>Lessons: Roll out Unit 4.</li>
            </ul>
            <li>April 2026</li>
            <ul>
                <li>Lessons: Roll out Unit 5.</li>
            </ul>
            <li>May 2026</li>
            <ul>
                <li>Lessons: Roll out Unit 6.</li>
            </ul>
            <li>June 2026</li>
            <ul>
                <li>Enabling PalWeb to function as a progressive web app (PWA) downloadable to mobile devices for
                    offline use.
                </li>
            </ul>
        </ul>
        <p>As you can see, next July should be the inauguration of the <b>PalWeb App</b>, which should be available on
            Android & iOS app stores. At that point, the fifth & final year of the project will begin. During that time,
            in addition to rolling out additional Units & content for the site, I will be exploring the potential of AI
            to provide features such as a chatbot that can read the application database & pages to provide answers to
            questions & may be used to practice Arabic with.</p>
        <p>Based on the state of the project at that time, I will determine the path forward. If there is interest in
            the project, I am still interested in creating a <b>LangWeb</b> framework of documentation tools on the
            basis of what I've built for PalWeb. I wanted to get started with that this year, but in reality it is best
            to focus on fully completing PalWeb now & move forward with that based on how this project turns out.</p>
    </div>

    <div class="wiki-content-block">
        <h1>Version History</h1>
        <ul>
            <li>01/10/2025 — PalWeb v2.1: Nabatean <b>(Current)</b></li>
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
</template>
