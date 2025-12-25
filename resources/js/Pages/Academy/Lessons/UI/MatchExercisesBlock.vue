<script setup>
import {onMounted, onUnmounted, reactive, ref} from "vue";
import {shuffle} from "lodash";
import {useExerciseBase} from "../../../../composables/useExerciseBlock.js";

const props = defineProps({
    block: {type: Object, required: true},
    results: {type: Array, default: null}
});

const {
    ActivityStore,
    processedItems,
} = useExerciseBase(props);

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
                starts: !props.results ? shuffle(item.pairs.map(p => p.start)) : item.pairs.map(p => p.start),
                ends: !props.results ? shuffle(item.pairs.map(p => p.end)) : item.pairs.map(p => p.end),
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

    let matchExercises;

    if (props.results) {
        const parentIds = [...new Set(props.results.filter(r => r.type === 'match_pair').map(r => r.parentId))];

        matchExercises = parentIds.map(parentId => {
            const pairs = props.results.filter(r => r.parentId === parentId);
            return {
                id: parentId,
                type: 'match',
                response: pairs
                    .filter(p => p.response !== null)
                    .map(p => ({start: p.start, end: p.response}))
            };
        });

    } else {
        matchExercises = ActivityStore.exercises.filter(ex => ex.type === 'match');
    }

    matchExercises.filter(ex => ex.type === 'match').forEach(ex => {
        const gridEl = blockRef.value.querySelector(`.exercise--match[data-item-id="${ex.id}"] .exercise--match-grid`);
        if (!gridEl) return;

        const gridRect = gridEl.getBoundingClientRect();
        const response = Array.isArray(ex.response) ? ex.response : [];

        const originalItem = props.block.items.find(i => i.id === ex.id);
        if (!originalItem) return;

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
            if (props.results) {
                const isCorrect = originalItem.pairs.some(p => p.start === pair.start && p.end === pair.end);
                status = isCorrect ? 'correct' : 'incorrect';
            }

            newLines.push({...coords, status, itemId: ex.id});
        });

        if (props.results) {
            originalItem.pairs.forEach(correctPair => {
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
    const exercise = ActivityStore.getExerciseById(itemId);

    if (props.results) {
        const pairResults = props.results.filter(r => r.parentId === itemId);

        const isCorrect = pairResults.some(res => {
            if (type === 'start') return res.start === value && res.correct;
            if (type === 'end') return res.end === value && res.correct;
            return false;
        });

        return isCorrect ? 'correct' : 'incorrect';
    }

    if (exercise?.response.some(p => p[type] === value)) {
        return 'committed';
    }
    return 'none';
};
</script>

<template>
    <div class="block--exercises" ref="blockRef">
        <div class="featured-title l">{{ block.exerciseType }}</div>
        <p>Match the two columns based on the prompt.</p>
        <template v-for="item in processedItems" :key="item.id">
            <div v-if="item.images.length" class="exercise-images">
                <img v-for="imgUrl in item.images" :src="imgUrl" alt="Reference Image">
            </div>
            <div class="exercise--match" :data-item-id="item.id">
                <div class="exercise-prompt">
                    <p>{{ item.prompt }}</p>
                </div>

                <div class="exercise--match-grid">
                    <div class="exercise--match-column">
                        <div v-for="val in matchColumns[item.id]?.starts" :key="val">
                            <button
                                :data-match-val="`${item.id}-start-${val}`"
                                :class="getMatchState(item.id, 'start', val)"
                                :disabled="!!results"
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
                                :disabled="!!results"
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
