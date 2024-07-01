@extends ('layouts.main')

@section('content')

    <x-lesson unit="3" lesson="2">
        <x-nav-modules
            img="https://upload.wikimedia.org/wikipedia/commons/f/f3/The_tall_palms_pattern_%28Palest-inian_embroidery-Ramallah_area%29.jpg"
            position="22%"/>
        <div x-data="{ tab: 'm1' }" @tab-changed.window="tab = $event.detail" class="tabs">
            @foreach (range(1, 3) as $m)
                <div x-show="tab === 'm{{ $m }}'">
                    @include('lessons.u3.l2.m' . $m, ['u' => 3, 'l' => 2, 'm' => $m, 'terms' => $terms, 'sentences' => $sentences])
                </div>
            @endforeach
        </div>
    </x-lesson>

@endsection
