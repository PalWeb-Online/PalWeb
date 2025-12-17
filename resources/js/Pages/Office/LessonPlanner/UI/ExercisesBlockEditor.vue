<script setup>
import Draggable from "vuedraggable";
import ToggleSingle from "../../../../components/ToggleSingle.vue";

const props = defineProps({
    block: {type: Object, required: true},
    addExercise: {type: Function, required: true},
    removeExercise: {type: Function, required: true},
});

const exerciseTypes = [
    'input',
    'match',
    'select',
];

const addExample = () => {
    props.block.examples.push({
        prompt: '',
        answer: '',
    });
};

const removeExample = (index) => {
    props.block.examples.splice(index, 1);
};

const addImage = (ex) => {
    ex.images.push('');
};

const removeImage = (ex, index) => {
    ex.images.splice(index, 1);
};

const addInputAnswer = (ex) => {
    ex.answers.push('');
};

const removeInputAnswer = (ex, index) => {
    ex.answers.splice(index, 1);
    if (ex.answers.length === 0) ex.answers.push('');
};

const addSelectOption = (ex) => {
    ex.options.push('');
};

const removeSelectOption = (ex, optionIndex) => {
    if (ex.options.length <= 2) return;

    ex.options.splice(optionIndex, 1);

    if (ex.answerIndex === optionIndex) {
        ex.answerIndex = 0;
    } else if (optionIndex < ex.answerIndex) {
        ex.answerIndex -= 1;
    }

    if (ex.answerIndex < 0 || ex.answerIndex >= ex.options.length) {
        ex.answerIndex = 0;
    }
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
    <div class="block--exercises">
        <div class="block-add-buttons">
            <div v-if="!props.block.exerciseType" v-for="exerciseType in exerciseTypes"
                 :key="exerciseType">
                <div class="add-button"
                     @click="props.addExercise({ blockId: props.block.id, type: exerciseType })">+
                </div>
                <div>{{ exerciseType }}</div>
            </div>
            <div v-else>
                <div class="add-button"
                     @click="props.addExercise({ blockId: props.block.id, type: props.block.exerciseType })">+
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
                        <span class="handle material-symbols-rounded" style="cursor:grab;">menu</span>
                        <button
                            type="button"
                            class="material-symbols-rounded"
                            @click="props.removeExercise({ blockId: props.block.id, exerciseId: ex.id })"
                        >
                            Delete
                        </button>
                    </div>

                    <div class="block-add-buttons">
                        <div>
                            <div class="add-button"
                                 @click="addImage(ex)">+
                            </div>
                            <div>image</div>
                        </div>
                    </div>
                    <div class="exercise-images">
                        <div v-for="(img, i) in ex.images">
                            <img v-if="ex.images[i]" :src="ex.images[i]" alt="No Image Found"/>
                            <div style="display: flex; gap: 0.8rem; align-items: center">
                                <button type="button"
                                        class="material-symbols-rounded"
                                        @click="removeImage(ex, i)"
                                >
                                    delete
                                </button>
                                <input v-model="ex.images[i]" :class="{ 'invalid': !ex.images[i] }" style="width: 100%;"
                                       placeholder="URL"/>
                            </div>
                        </div>
                    </div>

                    <div class="exercise-question">
                        <div class="material-symbols-rounded">help</div>
                        <input v-model="ex.prompt" :class="{ 'invalid': !ex.prompt }" style="width: 100%;"
                               placeholder="سؤال"/>
                    </div>

                    <template v-if="ex.type === 'select'">
                        <div class="exercise-answers">
                            <div class="exercise-select-option" v-for="(opt, i) in ex.options" :key="opt.id">
                                <button
                                    @click="ex.answerIndex = i"
                                    class="checkmark material-symbols-rounded"
                                    :class="{'active': i === ex.answerIndex}"
                                >
                                    check_circle
                                </button>
                                <input v-model="ex.options[i]"
                                       :class="{ 'invalid': !ex.options[i] }"
                                       style="flex:1"
                                       placeholder="خيار"/>
                                <button v-if="ex.options.length > 2"
                                        type="button"
                                        class="material-symbols-rounded"
                                        @click="removeSelectOption(ex, i)"
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
