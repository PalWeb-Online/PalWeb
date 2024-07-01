@extends ('layouts.main')

@section('content')

    <style>
        #main {
            background: none;
        }

        #flashcard {
            display: flex;
            width: 64rem;
            height: 38.4rem;
            background-color: white;
            margin: 4rem auto;
            border-radius: 1.6rem;
            padding: 0.4rem;
            font-family: "JetBrains Mono", monospace, "Vazirmatn", sans-serif;
            font-weight: 700;
        }

        .fl-cont {
            display: flex;
            flex-flow: column;
            border-radius: 1.2rem;
            margin: 0.4rem;
        }

        .fl-cont.main {
            flex-basis: 70%;
            outline: 1px solid var(--grn);
        }

        .fl-head {
            background-color: rgba(0, 166, 81, 0.2);
            border-radius: 1.2rem 1.2rem 0 0;
            display: flex;
            flex-flow: row wrap;
            justify-content: space-between;
            padding: 0.4rem;
            text-transform: uppercase;
            font-size: 2.4rem;
            color: white;
        }

        .fl-head-section {
            display: flex;
            flex-flow: row wrap;
        }

        .fl-head-section > * {
            background-color: rgba(0, 166, 81, 0.8);
            border-radius: 0.8rem;
            padding: 0.4rem 0.8rem;
            margin: 0.4rem;
            align-self: center;
        }

        .fl-body {
            flex-grow: 1;
            text-align: center;

            display: flex;
            flex-flow: column;
            align-items: center;
            justify-content: center;
        }

        .fl-body > div:first-child {
            margin-top: -3.2rem;
            margin-bottom: 1.6rem;
            font-size: 6.4rem;
        }

        .fl-body > div:nth-child(2) {
            font-size: 2.4rem;
        }

        .fl-cont.side {
            flex-basis: 30%;
            padding: 0;

            justify-content: space-between;
        }

        .fl-ex {
            background-color: rgba(0, 166, 81, 0.2);
            border-radius: 0.8rem;
            padding: 0.8rem 0;
            margin: 0.8rem;
            text-align: center;
        }

        .fl-ex.ap {
            background-color: rgba(255, 194, 14, 0.2)
        }

        .fl-ex.fem {
            background-color: rgba(236, 0, 140, 0.2)
        }

        .fl-ex.plr {
            background-color: rgba(102, 45, 145, 0.2)
        }

        .fl-ex.elt, .fl-ex.pp {
            background-color: rgba(0, 114, 188, 0.2)
        }

        img {
            /*margin: 0.8rem;*/
            /*border:1px solid var(--grn);*/
            border-radius: 1.2rem;
        }

        .fl-back {
            flex-grow: 1;
            display: grid;
            align-content: start;
            gap: 0.8rem;

            font-weight: 400;
            margin: 0.4rem;
            background-color: rgba(0, 166, 81, 0.2);
            border-radius: 1.2rem;
            padding: 0.8rem;
        }

        ol {
            border-radius: 0.8rem;
            /*background-color: rgba(0, 166, 81, 0.2);*/
            margin-top: -0.8rem;
            padding-left: 4rem
        }

        li {
            border-radius: 0.8rem;
            /*border: 1px solid var(--grn);*/
            background-color: white;
            margin: 0.8rem 0;
            padding: 0
        }

        li > div {
            padding: 1.2rem 1.6rem;
        }

        li > div:not(:first-child) {
            padding-bottom: 0.8rem;
        }

        .dictionary-gloss {
            padding: 1.6rem !important;
        }

        .dictionary-gloss .inline-chart {
            font-size: 1.6rem;
        }

        .fl-sentence-container {
            display: grid;
        }

        .fl-sentence-arb {
            display: flex;
            flex-flow: row-reverse;
            gap: 0.4rem;
            font-family: "Vazirmatn", sans-serif;
            font-weight: 700;
            font-size: 2.0rem;
        }

        .fl-sentence-term {
            padding: 0 0.4rem;
            border-radius: 0.8rem;
        }

        .fl-sentence-eng {
            font-size: 1.2rem;
            font-weight: 700;
            text-align: right;
        }
    </style>

    <div id="flashcard">
        <div class="fl-cont main">
            <div class="fl-head">
                <div class="fl-head-section">
                    <div>{{ $term->category }}</div>

                    {{--                    @if($term->category == "verb")--}}
                    {{--                        <div>{{ $term->category->patterns['verbal'][0] }}</div>--}}
                    {{--                    @endif--}}
                    @if($term->category == "noun")
                        <div>{{ $term->category->gender }}</div>
                    @endif
                </div>

                @if($term->root)
                    <div class="fl-head-section" style="flex-direction: row-reverse">
                        <div>{{ $term->root->rootArray()[0][0] }}</div>
                        <div>{{ $term->root->rootArray()[0][1] }}</div>
                        <div>{{ $term->root->rootArray()[0][2] }}</div>
                    </div>
                @endif
            </div>
            <div class="fl-body">
                <div>{{ $term->term }}</div>
                <div>{{ $term->slug }}</div>
            </div>
        </div>
        <div class="fl-cont side">
            <div>
                @foreach($term->inflections as $inflection)
                    <div class="fl-ex {{ $inflection->form }}">{{ $inflection->inflection }}</div>
                @endforeach
            </div>
            <img src="{{ asset('img/qr.png') }}"/>
        </div>
    </div>

    <div id="flashcard">
        <div class="fl-back">
            @foreach ($term->glosses as $index => $gloss)
                @php
                    if ($term->category == 'verb') {
                        if(in_array($gloss->attribute, ['unaccusative', 'passive', 'reflexive', 'reciprocal'])) {
                            $verbType = 'isPatient';
                        } elseif (in_array($gloss->attribute, ['transitive', 'causative', 'dative', 'complex'])) {
                            $verbType = 'hasObject';
                        } elseif (in_array($gloss->attribute, ['unergative', 'stative'])) {
                            $verbType = 'noPatient';
                        } elseif ($gloss->attribute == 'auxiliary') {
                            $verbType = 'auxiliary';
                        }
                    } else {
                        $verbType = '';
                    }
                @endphp

                <div class="data-box-contents">
                    <div class="dictionary-gloss">
                        <div class="inline-chart" style="padding: 0">
                            {{ $index+1 }}.

                            {{--                            @isset($gloss->attribute)--}}
                            {{--                                <div class="chart-item {{ $verbType }}" style="margin: 0.4rem 1.6rem">--}}
                            {{--                                    <div class="chart-title">{{ $gloss->attribute }}</div>--}}
                            {{--                                    @isset($gloss->structure)--}}
                            {{--                                        <div>{{ $gloss->structure }}</div>--}}
                            {{--                                    @endisset--}}
                            {{--                                </div>--}}
                            {{--                            @endisset--}}

                            {{ $gloss->gloss }}
                        </div>


                        @if(count($gloss->sentences) > 0)
                            <div class="fl-sentence-container {{ $verbType }}">
                                <div class="fl-sentence-arb">
                                    @foreach($gloss->sentences[0]->getTerms($gloss) as $sentenceTerm)
                                        @if(isset($sentenceTerm['exists']) && !isset($sentenceTerm['current']))
                                            <a href="/dictionary/{{ $sentenceTerm->category }}/{{ $sentenceTerm['translit'] }}"
                                               class="fl-sentence-term">
                                                <div>{{ $sentenceTerm['sent_term'] }}</div>
                                            </a>
                                        @else
                                            <a class="fl-sentence-term"
                                               style="cursor: default; {{ isset($sentenceTerm['current']) ? 'background-color: var(--dimyel)' : '' }}">
                                                <div>{{ $sentenceTerm['sent_term'] }}</div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="fl-sentence-eng">
                                    {{ $gloss->sentences[0]->trans }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
