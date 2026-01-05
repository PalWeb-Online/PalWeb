<script setup>
import Draggable from "vuedraggable";
import ToggleSingle from "../../../../components/ToggleSingle.vue";
import {useDocumentBuilder} from "../../../../composables/useDocumentBuilder.js";

const { addExercise, removeExercise, addPrompt, removePrompt, addSelectOption, removeSelectOption } = useDocumentBuilder();

const props = defineProps({
    block: {type: Object, required: true},
});

const addExample = () => {
    props.block.examples.push({
        prompt: '',
        answer: '',
    });
};

const removeExample = (index) => {
    props.block.examples.splice(index, 1);
};

const addInputAnswer = (ex) => {
    ex.answers.push('');
};

const removeInputAnswer = (ex, index) => {
    ex.answers.splice(index, 1);
    if (ex.answers.length === 0) ex.answers.push('');
};

const addMatchPair = (ex) => {
    ex.pairs.push({
        start: '',
        end: ''
    });
};

const removeMatchPair = (ex, pairIndex) => {
    if (ex.pairs.length <= 2) return;
    ex.pairs.splice(pairIndex, 1);
};
</script>

<template>
    <div class="block-editor--exercises">
        <div class="block-add-buttons">
            <div v-if="!props.block.exerciseType" v-for="exerciseType in ['match', 'select', 'input']"
                 :key="exerciseType">
                <div class="add-button"
                     @click="addExercise({ blockId: props.block.id, type: exerciseType })">+
                </div>
                <div>{{ exerciseType }}</div>
            </div>
            <div v-else>
                <div class="add-button"
                     @click="addExercise({ blockId: props.block.id, type: props.block.exerciseType })">+
                </div>
                <div>{{ props.block.exerciseType }}</div>
            </div>

            <div v-if="props.block.exerciseType === 'input'">
                <div class="add-button"
                     @click="addExample">+
                </div>
                <div>example</div>
            </div>
        </div>

        <div v-if="props.block.exerciseType === 'input'" class="exercise-item"
             v-for="(example, i) in props.block.examples" :key="i">
            <div class="block-meta">
                <div>example</div>
                <button
                    type="button"
                    class="material-symbols-rounded"
                    @click="removeExample(i)"
                >
                    delete
                </button>
            </div>

            <div class="exercise-question">
                <div class="material-symbols-rounded">help</div>
                <input
                    v-model="example.prompt"
                    :class="{ 'invalid': !example.prompt }"
                    style="width: 100%;"
                    placeholder="سؤال"
                />
            </div>

            <div class="exercise-answers">
                <div class="exercise-select-option">
                    <input
                        v-model="example.answer"
                        :class="{ 'invalid': !example.answer }"
                        style="flex: 1;"
                        placeholder="جواب"
                    />
                </div>
            </div>
        </div>

        <Draggable class="exercises-draggable"
                   v-if="props.block.items?.length"
                   :list="props.block.items"
                   itemKey="id"
                   handle=".handle"
        >
            <template #item="{ element: ex, index }">
                <div class="exercise-item">
                    <div class="block-meta">
                        <div style="flex-grow: 1">{{ index + 1 + '. ' + ex.type }}</div>
                        <span class="handle material-symbols-rounded">drag_indicator</span>
                        <button
                            type="button"
                            class="material-symbols-rounded"
                            @click="removeExercise({ blockId: props.block.id, exerciseId: ex.id })"
                        >
                            Delete
                        </button>
                    </div>

                    <div class="block-add-buttons">
                        <div v-if="ex.prompts.filter(p => p.type === 'text').length < 1">
                            <div class="add-button"
                                 @click="addPrompt(ex, 'text')">+
                            </div>
                            <div>text</div>
                        </div>
                        <div v-if="ex.prompts.filter(p => p.type === 'audio').length < 1">
                            <div class="add-button"
                                 @click="addPrompt(ex, 'audio')">+
                            </div>
                            <div>audio</div>
                        </div>
                        <div>
                            <div class="add-button"
                                 @click="addPrompt(ex, 'image')">+
                            </div>
                            <div>image</div>
                        </div>
                    </div>
                    <div class="exercise-prompt_build"
                         v-if="ex.prompts.some(p => p.type === 'image')"
                    >
                        <div v-for="image in ex.prompts.filter(p => p.type === 'image')" :key="image.id">
                            <img v-if="image.value" :src="image.value" alt="No Image Found"/>
                            <div style="display: flex; gap: 0.8rem; align-items: center">
                                <button type="button"
                                        class="material-symbols-rounded"
                                        @click="removePrompt(ex, image.id)"
                                >
                                    delete
                                </button>
                                <input v-model="image.value" :class="{ 'invalid': !image.value }" style="width: 100%;"
                                       placeholder="Image URL"/>
                            </div>
                        </div>
                    </div>
                    <div class="exercise-prompt_build"
                         v-if="ex.prompts.some(p => p.type === 'audio')"
                    >
                        <div v-for="audio in ex.prompts.filter(p => p.type === 'audio')" :key="audio.id">
                            <audio v-if="audio.value" :src="audio.value" controls/>
                            <div style="display: flex; gap: 0.8rem; align-items: center">
                                <button type="button"
                                        class="material-symbols-rounded"
                                        @click="removePrompt(ex, audio.id)"
                                >
                                    delete
                                </button>
                                <input v-model="audio.value" :class="{ 'invalid': !audio.value }" style="width: 100%;"
                                       placeholder="Audio URL"/>
                            </div>
                        </div>
                    </div>
                    <div class="exercise-prompt_build" style="grid-template-columns: 1fr"
                         v-if="ex.prompts.some(p => p.type === 'text')"
                    >
                        <div v-for="text in ex.prompts.filter(p => p.type === 'text')" :key="text.id"
                            style="display: flex; align-items: center">
                            <div class="material-symbols-rounded">help</div>
                            <input v-model="text.value"
                                   :class="{ 'invalid': !text.value }"
                                   style="width: 100%;"
                                   placeholder="سؤال"/>
                            <button type="button"
                                    class="material-symbols-rounded"
                                    @click="removePrompt(ex, text.id)"
                            >
                                delete
                            </button>
                        </div>
                    </div>

                    <template v-if="ex.type === 'select'">
                        <div class="exercise-answers">
                            <div class="exercise-select-option" v-for="(opt, i) in ex.options" :key="opt.id">
                                <button
                                    @click="ex.answerId = opt.id"
                                    class="checkmark material-symbols-rounded"
                                    :class="{'active': ex.answerId === opt.id}"
                                >
                                    check_circle
                                </button>
                                <input v-model="opt.text"
                                       :class="{ 'invalid': !opt.text }"
                                       style="flex:1"
                                       placeholder="خيار"/>
                                <button v-if="ex.options.length > 2"
                                        type="button"
                                        class="material-symbols-rounded"
                                        @click="removeSelectOption(ex, opt.id)"
                                >
                                    delete
                                </button>
                            </div>
                        </div>
                        <div class="exercise-select-buttons">
                            <div class="add-button" @click="addSelectOption(ex)">+</div>
                            <button
                                type="button"
                                class="checkmark material-symbols-rounded"
                                @click="ex.shuffleOptions = !ex.shuffleOptions"
                            >
                                {{ ex.shuffleOptions ? 'shuffle_on' : 'shuffle' }}
                            </button>
                        </div>
                    </template>

                    <template v-else-if="ex.type === 'input'">
                        <div class="exercise-answers">
                            <div class="exercise-select-option" v-for="(ans, i) in ex.answers" :key="i">
                                <input
                                    v-model="ex.answers[i]"
                                    :class="{ 'invalid': !ex.answers[i] }"
                                    style="flex: 1;"
                                    placeholder="جواب"
                                />
                                <button v-if="ex.answers.length > 1"
                                        type="button"
                                        class="material-symbols-rounded"
                                        @click="removeInputAnswer(ex, i)"
                                >
                                    delete
                                </button>
                            </div>
                        </div>
                        <div class="exercise-select-buttons">
                            <div class="add-button" @click="addInputAnswer(ex)">+</div>
                        </div>
                    </template>

                    <template v-else-if="ex.type === 'match'">
                        <div class="exercise-answers">
                            <div
                                class="exercise-select-option"
                                v-for="(pair, i) in ex.pairs"
                                :key="pair.id ?? i"
                                style="gap: 0.8rem;"
                            >
                                <input
                                    v-model="pair.start"
                                    :class="{ 'invalid': !pair.start }"
                                    style="flex: 1;"
                                    placeholder="Start"
                                />
                                ->
                                <input
                                    v-model="pair.end"
                                    :class="{ 'invalid': !pair.end }"
                                    style="flex: 1;"
                                    placeholder="End"
                                />
                                <button
                                    v-if="ex.pairs.length > 2"
                                    type="button"
                                    class="material-symbols-rounded"
                                    @click="removeMatchPair(ex, i)"
                                >
                                    delete
                                </button>
                            </div>
                        </div>
                        <div class="exercise-select-buttons">
                            <div class="add-button" @click="addMatchPair(ex)">+</div>
                        </div>
                    </template>
                </div>
            </template>
        </Draggable>
        <ToggleSingle label="shuffle" v-model="props.block.shuffle"/>
    </div>
</template>

<style scoped lang="scss">
.block-editor--exercises {
    display: grid;
    gap: 3.2rem;

    .exercises-draggable {
        display: grid;
        gap: 1.6rem;
    }

    .exercise-item {
        display: grid;
        gap: 1.6rem;
        padding: 1.6rem;
        border-radius: 0.5em;
        border: 0.1rem solid var(--color-medium-primary);
    }

    input {
        font-size: 1.4rem;
    }

    .block-meta {
        font-size: 2.0rem;
        font-weight: 700;
    }

    .exercise-question {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        direction: rtl;

        input {
            font-weight: 700;
        }
    }

    .exercise-answers {
        display: grid;
        gap: 0.8rem;
        direction: rtl;
    }

    .exercise-select-option {
        display: flex;
        align-items: center;
        gap: .8rem;
        font-family: var(--mono-font), monospace;
        font-weight: 700;

        button {
            color: var(--color-medium-primary);
        }

        button.checkmark {
            opacity: 0.33;
        }

        button.active {
            opacity: 1;
        }
    }

    .exercise-select-buttons {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        font-size: 1.4rem;
        direction: rtl;

        button {
            color: var(--color-dark-primary);
        }
    }
}
</style>
