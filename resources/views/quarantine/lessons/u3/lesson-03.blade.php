@extends ('layouts.main')

@section('content')

    <x-lesson unit="3" lesson="3">
        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/2/25/Wikipedia_backpack_Jungle.jpg"
            position="20%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u3.l3.m' . $m, ['u' => 3, 'l' => 3, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
