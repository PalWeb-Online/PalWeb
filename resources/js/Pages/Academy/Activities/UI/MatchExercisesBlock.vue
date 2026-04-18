<script setup>
import {onMounted, onUnmounted, reactive, ref} from "vue";
import {shuffle} from "lodash";
import {useExerciseBlock} from "../../../../composables/useExerciseBlock.js";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    ActivityStore,
    isViewingResults,
    processedItems,
} = useExerciseBlock(props);

const matchColumns = reactive({});

const blockRef = ref(null);

const matchState = reactive({
    pending: {
        itemId: null,
        type: null,
        value: null
    }
});

onMounted(async () => {
    if (props.block.exerciseType === 'match') {
        props.block.items.forEach(item => {
            matchColumns[item.id] = {
                starts: !isViewingResults.value ? shuffle(item.pairs.map(p => p.start)) : item.pairs.map(p => p.start),
                ends: !isViewingResults.value ? shuffle(item.pairs.map(p => p.end)) : item.pairs.map(p => p.end),
            };
        });
    }

    observer = new ResizeObserver(() => {
        updateLines();
    });

    if (blockRef.value) {
        observer.observe(blockRef.value);
    }
});

onUnmounted(() => {
    if (observer) observer.disconnect();
});

const lines = ref([]);
let observer = null;

const updateLines = () => {
    const newLines = [];
    if (!blockRef.value) return;

    props.block.items.forEach(ex => {
        const gridEl = blockRef.value.querySelector(`.exercise--match[data-item-id="${ex.id}"] .exercise--match-grid`);
        if (!gridEl) return;

        const gridRect = gridEl.getBoundingClientRect();

        const response = isViewingResults.value
            ? (ex.response || [])
            : (ActivityStore.getExerciseById(ex.id)?.response || []);

        const getCoords = (startVal, endVal) => {
            const startEl = gridEl.querySelector(`[data-match-val="${ex.id}-start-${startVal}"]`);
            const endEl = gridEl.querySelector(`[data-match-val="${ex.id}-end-${endVal}"]`);
            if (!startEl || !endEl) return null;

            const sR = startEl.getBoundingClientRect();
            const eR = endEl.getBoundingClientRect();

            return {
                x1: sR.left - gridRect.left,
                y1: (sR.top + sR.height / 2) - gridRect.top,
                x2: eR.right - gridRect.left,
                y2: (eR.top + eR.height / 2) - gridRect.top
            };
        };

        response.forEach(pair => {
            const coords = getCoords(pair.start, pair.end);
            if (!coords) return;

            let status = 'committed';
            if (isViewingResults.value) {
                const isCorrect = ex.pairs.some(p => p.start === pair.start && p.end === pair.end);
                status = isCorrect ? 'correct' : 'incorrect';
            }

            newLines.push({...coords, status, itemId: ex.id});
        });

        if (isViewingResults.value) {
            ex.pairs.forEach(correctPair => {
                const wasFound = response.some(r => r.start === correctPair.start && r.end === correctPair.end);

                if (!wasFound) {
                    const coords = getCoords(correctPair.start, correctPair.end);
                    if (coords) {
                        newLines.push({
                            ...coords,
                            status: 'missed',
                            itemId: ex.id
                        });
                    }
                }
            });
        }
    });

    lines.value = newLines;
};

const clearMatch = (itemId, type, value) => {
    const exercise = ActivityStore.getExerciseById(itemId);
    if (!exercise) return;

    const existingPairIndex = exercise.response.findIndex(p => p[type] === value);
    if (existingPairIndex !== -1) {
        exercise.response.splice(existingPairIndex, 1);

        setTimeout(updateLines, 0);
    }
};

const handleMatchClick = (itemId, type, value) => {
    const exercise = ActivityStore.getExerciseById(itemId);
    if (!exercise) return;

    if (exercise.response.some(p => p[type] === value)) {
        clearMatch(itemId, type, value);
        return;
    }

    if (matchState.pending.itemId === itemId && matchState.pending.type === type) {
        matchState.pending.value = (matchState.pending.value === value) ? null : value;
        return;
    }

    if (matchState.pending.itemId === itemId && matchState.pending.type !== type && matchState.pending.value) {
        const newPair = {
            start: type === 'start' ? value : matchState.pending.value,
            end: type === 'end' ? value : matchState.pending.value
        };

        exercise.response = exercise.response.filter(p =>
            p.start !== newPair.start && p.end !== newPair.end
        );

        exercise.response.push(newPair);

        matchState.pending.itemId = null;
        matchState.pending.type = null;
        matchState.pending.value = null;

        setTimeout(updateLines, 0);

    } else {
        matchState.pending.itemId = itemId;
        matchState.pending.type = type;
        matchState.pending.value = value;
    }
};

const getMatchState = (itemId, type, value) => {
    if (matchState.pending.itemId === itemId && matchState.pending.type === type && matchState.pending.value === value) {
        return 'pending';
    }

    const item = props.block.items.find(i => i.id === itemId);
    if (!item) return 'none';

    if (isViewingResults.value) {
        const isCorrect = item.pairs.some(p => {
            const isMatch = (type === 'start') ? p.start === value : p.end === value;
            return isMatch && p.correct;
        });

        return isCorrect ? 'correct' : 'incorrect';
    }

    const exercise = ActivityStore.getExerciseById(itemId);
    if (exercise?.response.some(p => p[type] === value)) {
        return 'committed';
    }

    return 'none';
};
</script>

<template>
    <div class="block--exercises" ref="blockRef">
        <h2>{{ block.exerciseType }}</h2>
        <ExercisesBlockPrompts :block="block"/>
        <template v-for="item in processedItems" :key="item.id">
            <div class="exercise--match" :data-item-id="item.id">
                <ExerciseItemPrompts :exercise="item" :isViewingResults="isViewingResults"/>

                <div class="exercise--match-grid">
                    <div class="exercise--match-column">
                        <div v-for="val in matchColumns[item.id]?.starts" :key="val">
                            <button
                                :data-match-val="`${item.id}-start-${val}`"
                                :class="getMatchState(item.id, 'start', val)"
                                :disabled="isViewingResults"
                                @click="handleMatchClick(item.id, 'start', val)"
                            >{{ val }}
                            </button>
                            <div style="margin-inline-start: -1.6rem"></div>
                        </div>
                    </div>
                    <svg class="exercise--match-overlay" v-if="lines.length">
                        <line v-for="(line, i) in lines.filter(l => l.itemId === item.id)" :key="i"
                              :class="line.status"
                              :x1="line.x1" :y1="line.y1" :x2="line.x2" :y2="line.y2"/>
                    </svg>
                    <div class="exercise--match-column">
                        <div v-for="val in matchColumns[item.id]?.ends" :key="val">
                            <div style="margin-inline-end: -1.6rem"></div>
                            <button
                                :data-match-val="`${item.id}-end-${val}`"
                                :class="getMatchState(item.id, 'end', val)"
                                :disabled="isViewingResults"
                                @click="handleMatchClick(item.id, 'end', val)"
                            >{{ val }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped lang="scss">
.exercise--match {
    gap: 3.2rem;

    .exercise--match-grid {
        display: flex;
        justify-content: space-between;
        gap: 8rem;
        position: relative;
    }

    .exercise--match-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;

        line {
            pointer-events: stroke;
            stroke: var(--color-accent-light);
            stroke-width: 0.4rem;
            stroke-linecap: round;

            &.correct {
                stroke: var(--color-medium-secondary);
            }

            &.missed {
                stroke: var(--color-medium-primary);
                stroke-dasharray: 0.8rem;
            }
        }
    }

    .exercise--match-column {
        min-width: 30%;
        display: flex;
        flex-direction: column;
        gap: 2.4rem;
        z-index: 1;

        & > div {
            display: flex;
            align-items: center;
            flex-basis: 20%;

            div {
                width: 2.4rem;
                height: 2.4rem;
                background: var(--color-medium-primary);
                border-radius: 50%;
                z-index: -1;
            }
        }

        button {
            flex-grow: 1;
            font-family: var(--ar-body-font), sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            background: var(--color-accent-light);
            color: var(--color-dark-secondary);
            padding: 1.2rem 2.4rem;
            border-radius: 0.8rem;
        }

        button.pending, button.committed:not(.incorrect), button.correct {
            color: white;
            background: var(--color-medium-secondary);
        }
    }
}
</style>
