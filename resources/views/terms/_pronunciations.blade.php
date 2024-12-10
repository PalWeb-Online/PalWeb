@foreach ($pronunciations as $pronunciation)
    <div class="pronunciation-item-wrapper">
        <div class="pronunciation-item">
            <div class="pronunciation-item-dialect">{{ $pronunciation->dialect->name }}</div>
            <div class="pronunciation-item-phonology">
                <div>
                    {{ $pronunciation->borrowed == true ? '(Borrowed)' : '' }}
                    {{ $pronunciation->translit }}
                    —
                    {{ $pronunciation->phonemic }}
                    {{ $pronunciation->phonetic }}
                </div>
            </div>
        </div>
        <div class="pronunciation-audios">
            @foreach ($pronunciation->audios as $audio)
                <div class="audio-item">
                    <img class="play" src="{{ asset('img/audio.svg') }}" alt="play"
                         onclick="{{ '_'.$audio->id }}.play()"/>
                    <div class="audio-item-info">
                        Lv. {{ $audio->speaker->fluency }}
                        <span style="text-transform: capitalize">{{ $audio->speaker->gender }}</span>
                        from {{ $audio->speaker->location->name }}
                    </div>

                    @unless ($audio->speaker->user->private)
                        <a href="{{ route('users.show', $audio->speaker->user) }}">
                            by {{ $audio->speaker->user->name }}
                        </a>
                        <img class="avatar" alt="User Avatar"
                             src="{{ asset('img/avatars/'.$audio->speaker->user->avatar) }}"/>
                    @else
                        <div>
                            by Anonymous
                        </div>
                    @endif

                    <script type="text/javascript">
                        const {{ '_'.$audio->id }} = new Howl({
                            src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $audio->filename }}.mp3']
                        });
                    </script>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
