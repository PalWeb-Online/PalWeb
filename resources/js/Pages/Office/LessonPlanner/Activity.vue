<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {useNavGuard} from "../../../composables/NavGuard.js";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import AppTip from "../../../components/AppTip.vue";
import {useNotificationStore} from "../../../stores/NotificationStore.js";
import TextBlockEditor from "./UI/TextBlockEditor.vue";
import AudioBlockEditor from "./UI/AudioBlockEditor.vue";
import TableBlockEditor from "./UI/TableBlockEditor.vue";
import ExercisesBlockEditor from "./UI/ExercisesBlockEditor.vue";

defineOptions({
    layout: Layout,
})

const NotificationStore = useNotificationStore();

const props = defineProps({
    lesson: Object,
    activity: Object,
});

const activity = useForm({
    lesson_id: props.lesson.id,
    title: 'Lesson ' + props.lesson.slug + ': ' + props.lesson.title,
    document: props.activity?.document || {
        schemaVersion: 1,
        blocks: []
    },
    published: props.activity?.published || false,
});

const uid = () => (globalThis.crypto?.randomUUID?.() ?? `id_${Date.now()}_${Math.random().toString(16).slice(2)}`);

const findIndexById = (arr, id) => arr.findIndex(x => x?.id === id);

const insertAtStart = (arr, item) => {
    arr.unshift(item);
};

const insertAfterId = (arr, afterId, item) => {
    if (!afterId) {
        arr.push(item);
        return;
    }
    const i = findIndexById(arr, afterId);
    arr.splice(i === -1 ? arr.length : i + 1, 0, item);
};

const removeById = (arr, id) => {
    const i = findIndexById(arr, id);
    if (i !== -1) arr.splice(i, 1);
};

const moveItem = (arr, fromIndex, toIndex) => {
    if (toIndex < 0 || toIndex >= arr.length) return;
    const [item] = arr.splice(fromIndex, 1);
    arr.splice(toIndex, 0, item);
};

const moveBlockUp = (blockId) => {
    const i = findIndexById(activity.document.blocks, blockId);
    if (i <= 0) return;
    moveItem(activity.document.blocks, i, i - 1);
};

const moveBlockDown = (blockId) => {
    const i = findIndexById(activity.document.blocks, blockId);
    if (i === -1 || i >= activity.document.blocks.length - 1) return;
    moveItem(activity.document.blocks, i, i + 1);
};

const blockTypes = [
    'text',
    'audio',
    'table',
    'exercises',
];

const blockFactories = {
    text: () => ({
        id: uid(),
        type: 'text',
        content: ''
    }),
    audio: () => ({
        id: uid(),
        type: 'audio',
        media: ''
    }),
    table: () => ({
        id: uid(),
        type: 'table',
        columns: [],
        rows: []
    }),
    exercises: () => ({
        id: uid(),
        type: 'exercises',
        exerciseType: null,
        shuffle: true,
        examples: [],
        items: []
    }),
};

const blockEditors = {
    text: TextBlockEditor,
    audio: AudioBlockEditor,
    table: TableBlockEditor,
    exercises: ExercisesBlockEditor,
};

const getBlockEditor = (type) => blockEditors[type] ?? TextBlockEditor;

const createBlock = (type) => (blockFactories[type] ?? blockFactories.text)();

const createColumn = () => ({
    id: uid(),
    label: '',
});

const createRow = () => ({
    id: uid(),
    cells: {},
});

// const createExample = () => ({
//     id: uid(),
//     prompt: '',
//     answer: '',
// });

const createExercise = (type = 'select') => {
    switch (type) {
        case 'input':
            return {
                id: uid(),
                type: 'input',
                images: [],
                prompt: '',
                answers: [''],
            };
        case 'match':
            return {
                id: uid(),
                type: 'match',
                images: [],
                prompt: '',
                pairs: [
                    {start: '', end: ''},
                    {start: '', end: ''},
                ],
            };
        case 'select':
        default:
            return {
                id: uid(),
                type: 'select',
                images: [],
                prompt: '',
                options: ['', ''],
                answerIndex: 0,
                shuffleOptions: true
            };
    }
};

const addBlock = ({afterBlockId = null, type = 'text', atStart = false} = {}) => {
    const block = createBlock(type);

    if (atStart) {
        insertAtStart(activity.document.blocks, block);
        return;
    }

    insertAfterId(activity.document.blocks, afterBlockId, block);
};

const getTableBlock = (blockId) => {
    const block = activity.document.blocks.find(b => b.id === blockId);
    if (!block || block.type !== 'table') return null;
    return block;
};

const addTableColumn = ({blockId, afterColumnId = null} = {}) => {
    const block = getTableBlock(blockId);
    if (!block) return;

    const col = createColumn();
    insertAfterId(block.columns, afterColumnId, col);

    for (const row of block.rows) {
        row.cells ??= {};
        row.cells[col.id] ??= '';
    }
};

const removeTableColumn = ({blockId, columnId} = {}) => {
    const block = getTableBlock(blockId);
    if (!block) return;

    for (const row of block.rows) {
        if (row.cells && Object.prototype.hasOwnProperty.call(row.cells, columnId)) {
            delete row.cells[columnId];
        }
    }

    removeById(block.columns, columnId);
};

const addTableRow = ({blockId, afterRowId = null} = {}) => {
    const block = getTableBlock(blockId);
    if (!block) return;

    const row = createRow();

    for (const col of block.columns) {
        row.cells[col.id] = '';
    }

    insertAfterId(block.rows, afterRowId, row);
};

const removeTableRow = ({blockId, rowId} = {}) => {
    const block = getTableBlock(blockId);
    if (!block) return;

    removeById(block.rows, rowId);
};

const addExercise = ({blockId, afterExerciseId = null, type = 'select'} = {}) => {
    const block = activity.document.blocks.find(b => b.id === blockId);
    if (!block || block.type !== 'exercises') return;

    if (!block.exerciseType) {
        block.exerciseType = type;

        if (type !== 'input') {
            block.examples = [];
        }

    } else if (block.exerciseType !== type) {
        alert(`This Exercises Block is locked to "${block.exerciseType}". Clear the block to change type.`);
        return;
    }

    const ex = createExercise(type);
    insertAfterId(block.items, afterExerciseId, ex);
};

const removeBlock = (blockId) => {
    removeById(activity.document.blocks, blockId);
};

const removeExercise = ({blockId, exerciseId}) => {
    const block = activity.document.blocks.find(b => b.id === blockId);
    if (!block || block.type !== 'exercises') return;

    removeById(block.items, exerciseId);

    if ((block.items?.length ?? 0) === 0) {
        block.exerciseType = null;
        block.examples = [];
    }
};

const isSaving = ref(false);

const hasNavigationGuard = computed(() => {
    return activity.isDirty && !isSaving.value;
});

const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const submitActivity = async ({publish}) => {
    isSaving.value = true;
    activity.published = !!publish;

    const method = props.activity?.id
        ? activity.patch.bind(activity)
        : activity.post.bind(activity);

    const url = props.activity?.id
        ? route('activities.update', props.activity.id)
        : route('activities.store');

    method(url, {
        onSuccess: () => {
            activity.defaults();
        },
        onError: () => {
            NotificationStore.addNotification('Oh no! The Activity could not be saved.', 'error');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    }, {preserveScroll: true});
};

const deleteActivity = () => {
    if (!confirm('Are you sure you want to delete this Activity?')) return;

    router.delete(route('activities.destroy', props.activity.id));
};

const isNonEmptyString = (v) => typeof v === 'string' && v.trim().length > 0;

const publishIssues = computed(() => {
    const issues = [];

    const blocks = activity.document?.blocks ?? [];
    const exerciseBlocks = blocks.filter(b => b?.type === 'exercises');
    const hasExerciseBlock = exerciseBlocks.length > 0;

    if (!hasExerciseBlock) {
        issues.push('At least one Exercises Block is required.');
    }

    blocks.forEach((block, blockIndex) => {
        const where = `Block ${blockIndex + 1} (${block.type})`;

        if (block.type === 'text') {
            if (!isNonEmptyString(block.content)) {
                issues.push(`${where}: Text Block content cannot be empty.`);
            }
        }

        if (block.type === 'audio') {
            if (!isNonEmptyString(block.media)) {
                issues.push(`${where}: Audio Block media cannot be empty.`);
            }
        }

        if (block.type === 'table') {
            const cols = block.columns ?? [];
            const rows = block.rows ?? [];

            if (cols.length < 1) issues.push(`${where}: Table Block must have at least 1 column.`);
            if (rows.length < 1) issues.push(`${where}: Table Block must have at least 1 row.`);

            cols.forEach((col, colIndex) => {
                if (!isNonEmptyString(col?.label)) {
                    issues.push(`${where}: column ${colIndex + 1} label cannot be empty.`);
                }
            });

            rows.forEach((row, rowIndex) => {
                cols.forEach((col, colIndex) => {
                    const cell = row?.cells?.[col.id];
                    if (!isNonEmptyString(cell)) {
                        issues.push(`${where}: row ${rowIndex + 1}, column ${colIndex + 1} cell cannot be empty.`);
                    }
                });
            });
        }

        if (block.type === 'exercises') {
            const items = block.items ?? [];

            (block.examples ?? []).forEach((ex, exIndex) => {
                if (!isNonEmptyString(ex?.prompt)) issues.push(`${where}: Example ${exIndex + 1} prompt is empty.`);
                if (!isNonEmptyString(ex?.answer)) issues.push(`${where}: Example ${exIndex + 1} answer is empty.`);
            });

            items.forEach((ex, exIndex) => {
                const exWhere = `${where}: Exercise ${exIndex + 1} (${ex.type})`;

                if (!isNonEmptyString(ex?.prompt)) {
                    issues.push(`${exWhere}: prompt cannot be empty.`);
                }

                (ex.images ?? []).forEach((img, imgIndex) => {
                    if (!isNonEmptyString(img)) {
                        issues.push(`${exWhere}: image ${imgIndex + 1} URL is empty.`);
                    }
                });

                if (ex.type === 'input') {
                    const answers = Array.isArray(ex.answers) ? ex.answers : [];
                    const hasAnyAnswer = answers.some(a => isNonEmptyString(a));
                    if (!hasAnyAnswer) {
                        issues.push(`${exWhere}: must have at least one non-empty accepted answer.`);
                    }
                }

                if (ex.type === 'select') {
                    const options = Array.isArray(ex.options) ? ex.options : [];
                    const anyEmpty = options.some(o => o === '');
                    if (anyEmpty) {
                        issues.push(`${exWhere}: option cannot be empty.`);
                    }
                }

                if (ex.type === 'match') {
                    const pairs = Array.isArray(ex.pairs) ? ex.pairs : [];
                    if (pairs.length < 2) {
                        issues.push(`${exWhere}: must have at least 2 pairs.`);
                    }
                    pairs.forEach((p, pairIndex) => {
                        if (!isNonEmptyString(p?.start)) {
                            issues.push(`${exWhere}: pair ${pairIndex + 1} start side cannot be empty.`);
                        }
                        if (!isNonEmptyString(p?.end)) {
                            issues.push(`${exWhere}: pair ${pairIndex + 1} end side cannot be empty.`);
                        }
                    });
                }
            });
        }
    });

    return issues;
});

const isPublishable = computed(() => publishIssues.value.length === 0);
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>lesson planner</h1>
    </div>
    <div id="app-body">
        <div class="form-body" style="width: min(96rem, 100%); padding: 0">
            <div class="unit-meta">
                <Link :href="route('lesson-planner.lesson', lesson.id)">
                    <- to Lesson
                </Link>
                <Link v-if="props.activity?.id" :href="route('activities.show', props.lesson.slug)">
                    View
                </Link>
            </div>
            <div class="featured-title l">
                Lesson {{ props.lesson.slug }}: Activity
            </div>
            <div class="field-item">
                <label>Title</label>
                <div class="field-input">
                    <input type="text" v-model="activity.title" placeholder="Title" required>
                </div>
            </div>

            <div class="featured-title m">blocks</div>

            <div class="block-add-buttons">
                <div v-for="blockType in blockTypes">
                    <div class="add-button"
                         @click="addBlock({ type: blockType, atStart: true })">+
                    </div>
                    <div>{{ blockType }}</div>
                </div>
            </div>

            <AppTip v-if="activity.document.blocks.length === 0">
                <p>No Blocks have been added to the Activity yet.</p>
            </AppTip>

            <div class="block-container-wrapper" v-for="(block, index) in activity.document.blocks" :key="block.id">
                <div class="block-container">
                    <div class="block-meta">
                        <div class="featured-title s" style="flex-grow: 1">
                            {{ index + 1 }}:
                            <span style="color: var(--color-dark-primary)">
                                {{ block.type }}{{ block.exerciseType ? ': ' + block.exerciseType : '' }}
                            </span>
                        </div>
                        <button type="button"
                                class="material-symbols-rounded"
                                @click="moveBlockUp(block.id)"
                                :disabled="index === 0">
                            move_up
                        </button>
                        <button type="button"
                                class="material-symbols-rounded"
                                @click="moveBlockDown(block.id)"
                                :disabled="index === activity.document.blocks.length - 1">
                            move_down
                        </button>
                        <button type="button"
                                class="material-symbols-rounded"
                                style="margin-inline-start: 0.8rem"
                                @click="removeBlock(block.id)">
                            delete
                        </button>
                    </div>
                    <component
                        :is="getBlockEditor(block.type)"
                        :block="block"
                        :addTableColumn="addTableColumn"
                        :removeTableColumn="removeTableColumn"
                        :addTableRow="addTableRow"
                        :removeTableRow="removeTableRow"
                        :addExercise="addExercise"
                        :removeExercise="removeExercise"
                    />
                </div>
                <div class="block-add-buttons">
                    <div v-for="blockType in blockTypes">
                        <div class="add-button"
                             @click="addBlock({ afterBlockId: block.id, type: blockType })">+
                        </div>
                        <div>{{ blockType }}</div>
                    </div>
                </div>
            </div>
        </div>

        <AppTip>
            <p>The Activity is currently {{ activity.published ? 'Published' : 'a Draft' }}.</p>
            <template v-if="!isPublishable">
                <p style="font-weight: 700">The Activity cannot be Published in the current state.</p>
                <ul>
                    <li v-for="(issue, i) in publishIssues" :key="i">{{ issue }}</li>
                </ul>
                <p v-if="activity.published" style="font-weight: 700">Because the Activity is already Published, the
                    current state cannot be saved except by reverting it to Draft.</p>
            </template>
        </AppTip>
        <AppTip v-if="activity.errors?.published">
            <p>{{ activity.errors.published }}</p>
        </AppTip>

        <div class="app-nav-interact">
            <div class="app-nav-interact-buttons">
                <button type="button"
                        @click="submitActivity({ publish: activity.published })"
                        :disabled="isSaving || !hasNavigationGuard || (activity.published && !isPublishable)">
                    Save
                </button>
                <button type="button" :disabled="!hasNavigationGuard" @click="activity.reset()">Reset</button>
                <button type="button"
                        @click="submitActivity({ publish: !activity.published })"
                        :disabled="isSaving"
                >
                    {{ hasNavigationGuard ? 'Save & ' : '' }} {{ activity.published ? 'Revert to Draft' : 'Publish' }}
                </button>
                <button type="button" @click="deleteActivity">Delete Activity</button>
            </div>
        </div>
    </div>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You have unsaved changes. Are you sure you want to leave this page? Unsaved changes will be lost."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>
