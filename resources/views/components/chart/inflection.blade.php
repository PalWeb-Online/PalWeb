<div class="inflection-chart-wrapper">
    <div class="inflection-chart">
        @if ($type === 'collective')
            <div class="inflection-chart-item" style="grid-column: span 2">
                <div>C</div>
                <div>
                    <div>{{ $term->term }}</div>
                    <div>{{ $term->translit }}</div>
                </div>

                <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                     onclick="{{ $term->audify() }}.play()"/>
                <script type="text/javascript">
                    var {{ $term->audify() }} = new Howl({
                        src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->audify() }}.mp3']
                    });
                </script>
            </div>
            @if($term->inflections->firstWhere('form', 'sing'))
                <div class="inflection-chart-item">
                    <div>S</div>
                    <div>
                        <div>{{ $term->inflections->firstWhere('form', 'sing')->inflection }}</div>
                        <div>{{ $term->inflections->firstWhere('form', 'sing')->translit }}</div>
                    </div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $term->inflections->firstWhere('form', 'sing')->audify() }}.play()"/>
                    <script type="text/javascript">
                        var {{ $term->inflections->firstWhere('form', 'sing')->audify() }} = new Howl({
                            src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->inflections->firstWhere('form', 'sing')->audify() }}.mp3']
                        });
                    </script>
                </div>
            @endif
            @if($term->inflections->firstWhere('form', 'pauc'))
                <div class="inflection-chart-item">
                    <div>P</div>
                    <div>
                        <div>{{ $term->inflections->firstWhere('form', 'pauc')->inflection }}</div>
                        <div>{{ $term->inflections->firstWhere('form', 'pauc')->translit }}</div>
                    </div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $term->inflections->firstWhere('form', 'pauc')->audify() }}.play()"/>
                    <script type="text/javascript">
                        var {{ $term->inflections->firstWhere('form', 'pauc')->audify() }} = new Howl({
                            src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->inflections->firstWhere('form', 'pauc')->audify() }}.mp3']
                        });
                    </script>
                </div>
            @endif
        @else
            <div class="inflection-chart-item"
                 style="grid-column: span {{ $term->inflections->where('form', 'fem')->isEmpty() ? count($term->inflections->where('form', 'plr')) * 2 : 1 }}">
                <div>{{ $type === 'singular' ? 'S' : 'M' }}</div>
                <div>
                    <div>{{ $term->term }}</div>
                    <div>{{ $term->translit }}</div>
                </div>

                <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                     onclick="{{ $term->audify() }}.play()"/>
                <script type="text/javascript">
                    var {{ $term->audify() }} = new Howl({
                        src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->audify() }}.mp3']
                    });
                </script>
            </div>
            @if($term->inflections->firstWhere('form', 'fem'))
                <div class="inflection-chart-item">
                    <div>F</div>
                    <div>
                        <div>{{ $term->inflections->firstWhere('form', 'fem')->inflection }}</div>
                        <div>{{ $term->inflections->firstWhere('form', 'fem')->translit }}</div>
                    </div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $term->inflections->firstWhere('form', 'fem')->audify() }}.play()"/>
                    <script type="text/javascript">
                        var {{ $term->inflections->firstWhere('form', 'fem')->audify() }} = new Howl({
                            src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->inflections->firstWhere('form', 'fem')->audify() }}.mp3']
                        });
                    </script>
                </div>
            @endif

            @foreach($term->inflections->where('form', 'plr')->all() as $plr)
                <div class="inflection-chart-item" style="grid-column: span 2">
                    <div>P</div>
                    <div>
                        <div>{{ $plr->inflection }}</div>
                        <div>{{ $plr->translit }}</div>
                    </div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $plr->audify() }}.play()"/>
                    <script type="text/javascript">
                        var {{ $plr->audify() }} = new Howl({
                            src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $plr->audify() }}.mp3']
                        });
                    </script>
                </div>
            @endforeach

            @if($term->inflections->firstWhere('form', 'elt'))
                <div class="inflection-chart-item" style="grid-column: span 2">
                    <div>E</div>
                    <div>
                        <div>{{ $term->inflections->firstWhere('form', 'elt')->inflection }}</div>
                        <div>{{ $term->inflections->firstWhere('form', 'elt')->translit }}</div>
                    </div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $term->inflections->firstWhere('form', 'elt')->audify() }}.play()"/>
                    <script type="text/javascript">
                        var {{ $term->inflections->firstWhere('form', 'elt')->audify() }} = new Howl({
                            src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->inflections->firstWhere('form', 'elt')->audify() }}.mp3']
                        });
                    </script>
                </div>
            @endif
        @endif
    </div>
</div>
