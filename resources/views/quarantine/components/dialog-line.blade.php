@props([
    'ltr' => false,
    'speaker' => false,
    'arb',
    'eng',
    'audio' => false
])

<div class="dialog-line-wrapper" style="direction: {{ $ltr ? 'ltr' : 'rtl' }}">
    <div class="dialog-line-container">
        @if($speaker)
            <div class="dialog-line-head">
                {{ $speaker }}
            </div>
        @endif
        <div class="dialog-line-body">
            <div class="dialog-arb">
                {{ $arb }}
            </div>
            @isset($eng)
                <div class="dialog-eng">
                    {{ $eng }}
                </div>
            @endisset
        </div>
    </div>

    @if($audio)
{{--        <img class="play" src="{{ asset('img/play.svg') }}" alt="play"--}}
{{--             onclick="{{ audify($eng) }}.play()" style="transform: {{ $ltr ? 'none' : 'rotate(180deg)' }}"/>--}}
{{--        <script type="text/javascript">--}}
{{--            var {{ audify($eng) }} = new Howl({--}}
{{--                src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ audify($eng) }}.mp3']--}}
{{--            });--}}
{{--        </script>--}}
    @endif
</div>
