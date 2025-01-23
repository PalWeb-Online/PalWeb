<x-page-head title="{{ __('academy') }}">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'academy')">{{ __('academy') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>{{ __('academy') }}</h1>
    <p>Welcome to the <b>PalWeb Academy</b>! Over the course of three <b>Levels</b>,
        the <b>Curriculum</b> will guide you through attaining an intermediate level in Palestinian
        Arabic from scratch.</p>
    <p>The <b>Curriculum</b> will expose you to essential vocabulary & covers every grammatical
        structure of the language. After that, you can use the resources in the <b>Dialogs</b> section of the site to
        widen
        your vocabulary and learn common turns of phrase.</p>
    <p>Each Level is divided into three Units built around a general theme. Each Unit contains nine Lessons, with each
        Lesson containing the following:</p>
    <ul>
        <li>one Grammar Skill</li>
        <li>one Vocabulary Skill</li>
        <li>a set of Exercises that test these two Skills</li>
        <li>one Idea section to help you get better at speaking ("Speak Like a Native"), to understand how the
            language works ("Know It All"), or to acquaint yourself further with Palestinian culture ("Get Cultured").
        </li>
        <li>a set of Dialogues that integrate everything learned in the Lesson</li>
    </ul>

    <h2>{{ __('dialogs') }}</h2>
    <p>The <b>Dialogs</b> section is intended to follow the content of the
        <b>Lessons</b> section, that is, the formal curriculum that should take learners up to an intermediate level in
        Palestinian Arabic. Because of this, the presentation of the following Dialogs is designed for intermediate- to
        advanced-level learners & presumes knowledge of the language's fundamentals. Hence, the focus is on learning new
        vocabulary &
        idiomatic expressions in a wider variety of natural situations, as well as improving listening comprehension.
    </p>
</div>
