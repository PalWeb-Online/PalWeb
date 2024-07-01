@extends ('layouts.main')

@section('content')

    <x-lesson unit="3" lesson="1">
        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/6/6a/Mixture_for_herbal_tea_05.jpg"
            position="40%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u3.l1.m' . $m, ['u' => 3, 'l' => 1, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
