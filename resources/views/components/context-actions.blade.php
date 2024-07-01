<x-dropdown class="context-actions">
    <x-slot name="trigger">
        <img src="{{ asset('/img/gear.svg') }}" alt="Options"/>
    </x-slot>
    <x-slot name="content">
        {{ $slot }}
    </x-slot>
</x-dropdown>
