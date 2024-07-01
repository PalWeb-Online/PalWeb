<template>
  <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" fill="none" stroke-width="3">
    <template v-if="type === 'default'">
      <circle cx="14" cy="14" r="12" fill="#fff" stroke="#e5e7eb" />
      <path d="M26 14c0-6.627-5.373-12-12-12" stroke="currentColor" stroke-linecap="round" />
    </template>
    <template v-else-if="type === 'inverted'">
      <circle cx="14" cy="14" r="12" stroke="currentColor" class="opacity-25" />
      <path d="M26 14c0-6.627-5.373-12-12-12" stroke-linecap="round" stroke="currentColor" class="opacity-75" />
    </template>
  </svg>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'default',
            },
        },
    }
</script>
