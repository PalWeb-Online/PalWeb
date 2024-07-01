<template>
    <button :class="$page.props.brandColor" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none transition ease-in-out duration-150">
        <slot />
    </button>
</template>
