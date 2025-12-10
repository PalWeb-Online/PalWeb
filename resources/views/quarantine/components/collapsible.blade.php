<div class="collapsible"
     x-data="{ open: false }"
     x-init="() => $watch('open', value => {
             if (value) {
                 $nextTick(() => {
                     $refs.contentBox.style.maxHeight = $refs.contentBox.scrollHeight + 'px';
                 });
             } else {
                 $refs.contentBox.style.maxHeight = null;
             }
         })"
>
    <button @click="open = !open" class="collapsible-head">
        <span>{{ $title }}</span>
        <span class="material-symbols-rounded">ads_click</span>
    </button>

    <div class="collapsible-body"
         x-ref="contentBox"
         x-show.transition.origin.top="open"
    >
        {{ $slot }}
    </div>
</div>
