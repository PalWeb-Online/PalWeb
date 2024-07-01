@foreach ($pronunciations as $pronunciation)
    <div class="pronunciation-li">
        <div class="pronunciation-li-dialect">{{ $pronunciation->dialect->name }}</div>

        <div class="pronunciation-li-phonology">
            <div>
                {{ $pronunciation->borrowed == true ? '(Borrowed)' : '' }}
                {{ $pronunciation->translit }}
                —
                {{ $pronunciation->phonemic }}
                {{ $pronunciation->phonetic }}
            </div>
        </div>

        <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
             onclick="{{ $pronunciation->audify() }}.play()"/>
        
        <script type="text/javascript">
            var {{ $pronunciation->audify() }} = new Howl({
                src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $pronunciation->audify() }}.mp3']
            });
        </script>
    </div>
@endforeach
