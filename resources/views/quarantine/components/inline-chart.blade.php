@props([
  'title',
  'subtitle',
  'link',
  'heading',
  'type' => [
    'VOCABULARY' => 'vocab',
    'DIALOGUE' => 'dialog'
  ]
])

@if(isset($link))
    <a href="{{ $link }}" class="inline-chart {{ isset($heading) ? 'title' : '' }}">
        @if(in_array($title, ['VOCABULARY', 'DIALOGUE']))
            <a class="chart-item {{ $type[$title] }}">
                @else
                    <div class="chart-item">
                        @endif
                        <div class="chart-title">{{ $title }}</div>
                        <div class="chart-subtitle">{{ $subtitle }}</div>
                    </div>
            </a>
        @else
            <div class="inline-chart {{ isset($heading) ? 'title' : '' }}">
                @if(in_array($title, ['VOCABULARY', 'DIALOGUE']))
                    <div class="chart-item {{ $type[$title] }}">
                        @else
                            <div class="chart-item">
                                @endif
                                <div class="chart-title">{{ $title }}</div>
                                @isset($subtitle)
                                    <div class="chart-subtitle">{{ $subtitle }}</div>
                                @endisset
                            </div>
                    </div>
@endif
