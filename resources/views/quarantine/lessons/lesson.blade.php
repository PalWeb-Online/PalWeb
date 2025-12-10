@extends ('layouts.main')

@section('content')
    <x-page-head :title="__('lessons.' . $unit . '0' . $lesson)">
        <x-link :href="route('academy.index')">{{ __('academy') }}</x-link>
        <x-link :href="route('academy.unit', $unit)">{{ __('unit') }} {{ $unit }}</x-link>
        <x-link :href="route('academy.lesson', [$unit, $lesson])">{{ __('lesson') }} {{ $unit.'0'.$lesson }}</x-link>
    </x-page-head>

    @include("lessons.pages." . $unit . '.' . $lesson, ["terms" => $terms])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var shuffleGroups = document.querySelectorAll('.shuffle');
            shuffleGroups.forEach(function (shuffleParent) {
                for (var i = shuffleParent.children.length; i >= 0; i--) {
                    shuffleParent.appendChild(shuffleParent.children[Math.random() * i | 0]);
                }
            });

            var activityFills = document.querySelectorAll('.activity-fill');
            var counter = (function () {
                var i = 0;
                return function () {
                    return i++;
                }
            })();

            activityFills.forEach(function (activityFill) {
                var button = activityFill.querySelector('button');
                var input = activityFill.querySelector('input');
                var qid = 'q' + counter();
                button.setAttribute('data-qid', qid);
                input.setAttribute('data-qid', qid);

                button.addEventListener('click', function (e) {
                    var ans = input.getAttribute('data-ans');
                    if (input.value == ans) {
                        activityFill.classList.toggle('correct');
                        button.disabled = true;
                        input.disabled = true;
                        fx_correct.play();
                    } else {
                        input.style.color = 'var(--red)';
                        fx_incorrect.play();
                    }
                });
            });
        });
    </script>

@endsection
