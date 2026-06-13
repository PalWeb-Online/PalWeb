<script setup>
import {computed} from 'vue';
import MarkdownIt from 'markdown-it';
import DOMPurify from 'dompurify';

const props = defineProps({
    block: {type: Object, required: true}
});

const md = new MarkdownIt();

const defaultRender =
    md.renderer.rules.link_open ||
    function (tokens, idx, options, env, self) {
        return self.renderToken(tokens, idx, options);
    };

md.renderer.rules.link_open = function (tokens, idx, options, env, self) {
    const href = tokens[idx].attrGet('href');

    if (href?.startsWith('wiki:')) {
        const slug = href.replace('wiki:', '');
        tokens[idx].attrSet('href', route('wiki.show', slug));
        tokens[idx].attrSet('data-internal', 'true');

    } else if (href?.startsWith('http')) {
        tokens[idx].attrSet('target', '_blank');
        tokens[idx].attrSet('rel', 'noopener noreferrer');
    }

    return defaultRender(tokens, idx, options, env, self);
};

const rendered = computed(() => {
    if (!props.block.content) return '';
    return DOMPurify.sanitize(
        md.render(props.block.content)
    );
});
</script>

<template>
    <div class="block--text" v-html="rendered"/>
</template>
