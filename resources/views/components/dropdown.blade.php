<div {{ $attributes->merge(['class' => 'dropdown-container']) }}
     x-data="{ open: false }"
     @mouseenter="open = true"
     @mouseleave="open = false"
     @click.outside="open = false"
     @close.stop="open = false"
>

    {{ $trigger }}

    <div class="dropdown-wrapper"
         style="display: none"
         x-show="open">
        <div class="dropdown-menu">
            {{ $content }}
        </div>
    </div>

</div>
