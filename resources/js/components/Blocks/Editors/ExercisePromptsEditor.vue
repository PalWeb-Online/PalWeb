<script setup>
import { computed } from "vue";
import { useDocumentBuilder } from "../../../../composables/useDocumentBuilder.js";

const props = defineProps({
    owner: { type: Object, required: true },
    title: { type: String, default: "" },
});

const { addPrompt, removePrompt } = useDocumentBuilder();

const prompts = computed(() => props.owner?.prompts ?? []);

const hasText = computed(() => prompts.value.filter(p => p.type === "text").length >= 1);
const hasAudio = computed(() => prompts.value.filter(p => p.type === "audio").length >= 1);
</script>

<template>
    <div class="block-add-buttons">
        <div v-if="!hasText">
            <div class="add-button"
                 @click="addPrompt(owner, 'text')">+
            </div>
            <div>text</div>
        </div>
        <div v-if="!hasAudio">
            <div class="add-button"
                 @click="addPrompt(owner, 'audio')">+
            </div>
            <div>audio</div>
        </div>
        <div>
            <div class="add-button"
                 @click="addPrompt(owner, 'image')">+
            </div>
            <div>image</div>
        </div>
    </div>
    <div class="exercise-prompt_build"
         v-if="prompts.some(p => p.type === 'image')"
    >
        <div v-for="image in prompts.filter(p => p.type === 'image')" :key="image.id">
            <img v-if="image.value" :src="image.value" alt="No Image Found"/>
            <div style="display: flex; gap: 0.8rem; align-items: center">
                <button type="button"
                        class="material-symbols-rounded"
                        @click="removePrompt(owner, image.id)"
                >
                    delete
                </button>
                <input v-model="image.value" :class="{ 'invalid': !image.value }"
                       style="width: 100%;"
                       placeholder="Image URL"/>
            </div>
        </div>
    </div>
    <div class="exercise-prompt_build"
         v-if="prompts.some(p => p.type === 'audio')"
    >
        <div v-for="audio in prompts.filter(p => p.type === 'audio')" :key="audio.id">
            <audio v-if="audio.value" :src="audio.value" controls/>
            <div style="display: flex; gap: 0.8rem; align-items: center">
                <button type="button"
                        class="material-symbols-rounded"
                        @click="removePrompt(owner, audio.id)"
                >
                    delete
                </button>
                <input v-model="audio.value" :class="{ 'invalid': !audio.value }"
                       style="width: 100%;"
                       placeholder="Audio URL"/>
            </div>
        </div>
    </div>
    <div class="exercise-prompt_build" style="grid-template-columns: 1fr"
         v-if="prompts.some(p => p.type === 'text')"
    >
        <div v-for="text in prompts.filter(p => p.type === 'text')" :key="text.id"
             style="display: flex; align-items: center">
            <div class="material-symbols-rounded">help</div>
            <input v-model="text.value"
                   :class="{ 'invalid': !text.value }"
                   style="width: 100%;"
                   placeholder="سؤال"/>
            <button type="button"
                    class="material-symbols-rounded"
                    @click="removePrompt(owner, text.id)"
            >
                delete
            </button>
        </div>
    </div>
</template>
