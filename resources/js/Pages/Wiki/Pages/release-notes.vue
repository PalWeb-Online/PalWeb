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
            for more information on how to use the various features of the site. Remember, if you find anything that
            doesn't work or look as expected or have any other suggestions for improvement, write to me using the <b>Send
                Feedback</b> button in the Sidebar (you must be logged in).
        </p>

        <h2>Roadmap</h2>
        <p>PalWeb is a multidisciplinary project that began in Summer 2022 & to this day is run by a single person.
            There are two main reasons for this:</p>
        <p>Firstly, from Summer 2022 to Summer 2025, I taught myself everything I know about web development from
            scratch. I spent about half of that time becoming competent at PHP & the second half becoming competent at
            Javascript, in addition to learning many lessons pertaining to web design, server management, etc. All of
            this came together for PalWeb 2.0 — a single-page application (SPA) that brings these two languages together
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
        <p>But, as I've said, PalWeb 2.0 marks a turning point for the project. I'm now much faster at what I do, so I
            have more time to develop the project more broadly. I'm also better at estimating the time needed to build
            new features & reach certain milestones, since I already have a high-level idea of how I will approach the
            work. Therefore, I have more or less planned out the next two years of what will ultimately be a five-year
            project. Over the next year, I will be rolling out new features incrementally, giving myself
            time both for coding & for outreach. Here's what I will be working on:</p>
        <ul>
            <li>August 2025</li>
            <ul>
                <li>Quizzer: Ability to Quiz a Deck & receive a score; the score is not saved for the user.</li>
            </ul>
            <li>September 2025</li>
            <ul>
                <li>myProgress: Saving user scores & interface to visualize score history per model & globally.</li>
            </ul>
            <li>October 2025</li>
            <ul>
                <li>Lessons: Navigation interface; reinstate Unit 1 content without Skills & Dialogs.</li>
            </ul>
            <li>November 2025</li>
            <ul>
                <li>Quizzer: Ability to Quiz Dialogs. Create Unit 1 Dialogs.</li>
            </ul>
            <li>December 2025</li>
            <ul>
                <li>Quizzer: Ability to Quiz various types of Skills. Create Unit 1 Skills.</li>
            </ul>
            <li>January-February 2026</li>
            <ul>
                <li>Lessons: Roll out Unit 2.</li>
            </ul>
            <li>March-April 2026</li>
            <ul>
                <li>Lessons: Roll out Unit 3.</li>
            </ul>
            <li>May-June 2026</li>
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
            <li>01/07/2025 — PalWeb v2.0 <b>(Current)</b></li>
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
