@props([
    'audio' => false,
])

@if($audio)
    <div class="audio-item">
        <img class="play" src="{{ asset('img/audio.svg') }}" alt="Play"
             onclick="{{ '_'.$audio->id }}.play()"/>
        <script type="text/javascript">
            const {{ '_'.$audio->id }} = new Howl({
                src: ['https://abdulbaha.fra1.digitaloceanspaces.com/audios/{{ $audio->filename }}']
            });
        </script>

        <div class="mini-user-profile">
            @unless($audio->speaker->user->private)
                <div>by
                    <a href="{{ route('speaker.show', $audio->speaker) }}">{{ $audio->speaker->user->name }}</a>
                </div>
                <img class="avatar" alt="User Avatar"
                     src="{{ asset('img/avatars/'.$audio->speaker->user->avatar) }}"/>
            @else
                <div>by
                    <a href="{{ route('speaker.show', $audio->speaker) }}">Speaker #{{$audio->speaker->id}}</a>
                </div>
            @endif
        </div>

        <div class="audio-item-info">
            {{ $audio->speaker->fluency_alias }}
            <span style="text-transform: capitalize">{{ $audio->speaker->gender !== 'other' ? $audio->speaker->gender : '' }}</span>
            Speaker from {{ $audio->speaker->location->name_ar }} ({{ $audio->speaker->location->name_en }})
        </div>
        <div class="audio-item-date">
            {{ $audio->created_at->format('j F Y') }}
        </div>

        @auth
            @if($audio->speaker->user_id === auth()->user()->id)
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
