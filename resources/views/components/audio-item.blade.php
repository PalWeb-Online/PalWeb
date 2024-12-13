@props([
    'audio' => false,
])

@if($audio)
    <div class="audio-item">
        <img class="play" src="{{ asset('img/audio.svg') }}" alt="play"
             onclick="{{ '_'.$audio->id }}.play()"/>

        @unless ($audio->speaker->user->private)
            <div class="mini-user-profile">
                <div>by
                    <a href="{{ route('audios.speaker', $audio->speaker) }}">{{ $audio->speaker->user->name }}</a>
                </div>
                <img class="avatar" alt="User Avatar"
                     src="{{ asset('img/avatars/'.$audio->speaker->user->avatar) }}"/>
            </div>
        @else
            <div>by
                <a href="{{ route('audios.speaker', $audio->speaker) }}">Speaker #{{$audio->speaker->id}}</a>
            </div>
        @endif

        <div class="audio-item-info">
            Lv. {{ $audio->speaker->fluency }}
            {{ $audio->speaker->dialect->name }}
            <span style="text-transform: capitalize">{{ $audio->speaker->gender }}</span>
            from {{ $audio->speaker->location->name }}
        </div>

        <script type="text/javascript">
            const {{ '_'.$audio->id }} = new Howl({
                src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $audio->filename }}.mp3']
            });
        </script>

        @auth
            @if($audio->speaker_id === auth()->user()->id)

                <form method="post" action="{{ route('audios.destroy', $audio) }}">
                    @csrf
                    @method('DELETE')
                    <img
                        onclick="if (confirm('Are you sure you want to delete this Audio?')) this.closest('form').submit()"
                        class="trash" alt="Delete"
                        src="{{ asset('img/trash.svg') }}"/>
                </form>

            @endif
        @endauth
    </div>
@endif
