<div id="academy-map" class="academy-map-toggle">
    <div class="featured-title l">{{ __('map') }}</div>

    <div id="unit-nav">
        <div class="unit-nav-units">
            <div>{{ __('unit') }}</div>
            <x-link :href="route('academy.unit', '1')" class="{{ $unit == '1' ? 'active' : '' }}">1</x-link>
            <x-link :href="route('academy.unit', '2')" class="{{ $unit == '2' ? 'active' : '' }}">2</x-link>
            <x-link :href="route('academy.unit', '3')" class="{{ $unit == '3' ? 'active' : '' }}">3</x-link>
        </div>
        <div class="unit-nav-lessons">
            <x-link :href="route('academy.lesson', ['1', '1'])"
                    class="lesson-nav-link {{$lesson == '1' ? 'active' : '' }}">1
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '2'])"
                    class="lesson-nav-link {{$lesson == '2' ? 'active' : '' }}">2
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '3'])"
                    class="lesson-nav-link {{$lesson == '3' ? 'active' : '' }}">3
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '4'])"
                    class="lesson-nav-link {{$lesson == '4' ? 'active' : '' }}">4
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '5'])"
                    class="lesson-nav-link {{$lesson == '5' ? 'active' : '' }}">5
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '6'])"
                    class="lesson-nav-link {{$lesson == '6' ? 'active' : '' }}">6
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '7'])"
                    class="lesson-nav-link {{$lesson == '7' ? 'active' : '' }}">7
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '8'])"
                    class="lesson-nav-link {{$lesson == '8' ? 'active' : '' }}">8
            </x-link>
            <x-link :href="route('academy.lesson', ['1', '9'])"
                    class="lesson-nav-link {{$lesson == '9' ? 'active' : '' }}">9
            </x-link>
        </div>
    </div>
</div>
