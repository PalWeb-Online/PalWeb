<div class="box {{ $type }}">
    <div class="preamble">

        @if($type === "grammar")
            <div>{{ __('grammar') }} | قواعد</div>

        @elseif($type === "vocab")
            <div>{{ __('vocabulary') }} | مفردات</div>

        @elseif($type === "dialog")
            <div>{{ __('dialogue') }} | محادثة</div>

        @elseif($type === "text")
            <div>{{ __('text') }} | نصّ</div>

        @else
        @endif

        <div>{{ __('module') }} {{ $num }}</div>
    </div>

    <h1>{{ $title }}</h1>

    {{ $slot }}

</div>
