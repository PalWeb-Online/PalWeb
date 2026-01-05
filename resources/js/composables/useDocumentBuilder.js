import TextBlockEditor from "../Pages/Office/LessonPlanner/UI/TextBlockEditor.vue";
import AudioBlockEditor from "../Pages/Office/LessonPlanner/UI/AudioBlockEditor.vue";
import TableBlockEditor from "../Pages/Office/LessonPlanner/UI/TableBlockEditor.vue";
import ExercisesBlockEditor from "../Pages/Office/LessonPlanner/UI/ExercisesBlockEditor.vue";
import {inject, provide} from "vue";
import SentenceBlockEditor from "../Pages/Office/LessonPlanner/UI/SentenceBlockEditor.vue";
import ChartBlockEditor from "../Pages/Office/LessonPlanner/UI/ChartBlockEditor.vue";
import ContainerBlockEditor from "../Pages/Office/LessonPlanner/UI/ContainerBlockEditor.vue";

const symbol = Symbol('document-builder');

export function useDocumentBuilder(documentBlocks = null) {
    const blocksArray = documentBlocks || inject(symbol, null);

    const provideBuilder = () => {
        provide(symbol, blocksArray);
    };

    const uid = () => (globalThis.crypto?.randomUUID?.() ?? `id_${Date.now()}_${Math.random().toString(16).slice(2)}`);

    const findIndexById = (arr, id) => arr.findIndex(x => x?.id === id);

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

    const blockEditors = {
        container: ContainerBlockEditor,
        text: TextBlockEditor,
        audio: AudioBlockEditor,
        table: TableBlockEditor,
        exercises: ExercisesBlockEditor,
        chart: ChartBlockEditor,
        sentence: SentenceBlockEditor,
    };

    const getBlockEditor = (type) => blockEditors[type] ?? TextBlockEditor;

    const blockFactories = {
        container: () => ({ id: uid(), type: 'container', title: '', blocks: [] }),
        text: () => ({ id: uid(), type: 'text', content: '' }),
        audio: () => ({ id: uid(), type: 'audio', media: '' }),
        table: () => ({ id: uid(), type: 'table', columns: [], rows: [] }),
        chart: () => ({ id: uid(), type: 'chart', title: '', rows: [] }),
        sentence: () => ({ id: uid(), type: 'sentence', model: null, custom: null }),
        exercises: () => ({
            id: uid(),
            type: 'exercises',
            exerciseType: null,
            shuffle: true,
            examples: [],
            items: []
        }),
    };

    const addBlock = (arr, { afterBlockId = null, type = 'text', atStart = false } = {}) => {
        const block = blockFactories[type]();

        if (atStart) {
            arr.unshift(block);

        } else {
            insertAfterId(arr, afterBlockId, block);
        }

        return block;
    };

    const removeBlock = (arr, blockId) => {
        removeById(arr, blockId);
    };

    const moveBlock = (arr, blockId, direction) => {
        const i = findIndexById(arr, blockId);
        if (i === -1) return;

        const toIndex = direction === 'up' ? i - 1 : i + 1;
        if (toIndex < 0 || toIndex >= arr.length) return;

        const [item] = arr.splice(i, 1);
        arr.splice(toIndex, 0, item);
    };

    const getBlockById = (blockId) => {
        const blocks = blocksArray?.value || blocksArray || [];
        return blocks.find(b => b.id === blockId);
    };

    const applyChartTemplate = (block, templateType) => {
        const templates = {
            person: [
                { id: uid(), items: [{ key: '1S', ar: '', tr: '' }] },
                { id: uid(), items: [{ key: '1P', ar: '', tr: '' }] },
                { id: uid(), items: [{ key: '2M', ar: '', tr: '' }, { key: '2F', ar: '', tr: '' }] },
                { id: uid(), items: [{ key: '2P', ar: '', tr: '' }] },
                { id: uid(), items: [{ key: '3M', ar: '', tr: '' }, { key: '3F', ar: '', tr: '' }] },
                { id: uid(), items: [{ key: '3P', ar: '', tr: '' }] },
            ],
            inflection: [
                { id: uid(), items: [{ key: 'M', ar: '', tr: '' }, { key: 'F', ar: '', tr: '' }] },
                { id: uid(), items: [{ key: 'P', ar: '', tr: '' }] },
            ]
        };

        if (templates[templateType]) {
            block.rows = JSON.parse(JSON.stringify(templates[templateType]));
        }
    };

    const addTableColumn = ({blockId, afterColumnId = null} = {}) => {
        const block = getBlockById(blockId);
        if (!block) return;

        const col = {
            id: uid(),
            label: '',
        };

        insertAfterId(block.columns, afterColumnId, col);

        for (const row of block.rows) {
            row.cells ??= {};
            row.cells[col.id] ??= '';
        }
    };

    const removeTableColumn = ({blockId, columnId} = {}) => {
        const block = getBlockById(blockId);
        if (!block) return;

        for (const row of block.rows) {
            if (row.cells && Object.prototype.hasOwnProperty.call(row.cells, columnId)) {
                delete row.cells[columnId];
            }
        }

        removeById(block.columns, columnId);
    };

    const addTableRow = ({blockId, afterRowId = null} = {}) => {
        const block = getBlockById(blockId);
        if (!block) return;

        const row = {
            id: uid(),
            cells: {},
        };

        for (const col of block.columns) {
            row.cells[col.id] = '';
        }

        insertAfterId(block.rows, afterRowId, row);
    };

    const removeTableRow = ({blockId, rowId} = {}) => {
        const block = getBlockById(blockId);
        if (!block) return;

        removeById(block.rows, rowId);
    };

    const createExercise = (type = 'select') => {
        const base = {
            id: uid(),
            type: type,
            prompts: [
                { id: uid(), type: 'text', value: '' }
            ],
        };

        switch (type) {
            case 'input':
                return {
                    ...base,
                    answers: [''],
                };
            case 'match':
                return {
                    ...base,
                    pairs: [
                        {start: '', end: ''},
                        {start: '', end: ''},
                    ],
                };
            case 'select':
            default:
                return {
                    ...base,
                    options: [
                        { id: uid(), text: '' },
                        { id: uid(), text: '' }
                    ],
                    answerId: null,
                    shuffleOptions: true
                };
        }
    };

    const addExercise = ({blockId, afterExerciseId = null, type = 'select'} = {}) => {
        const block = getBlockById(blockId);
        if (!block) return;

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

    const removeExercise = ({blockId, exerciseId}) => {
        const block = getBlockById(blockId);
        if (!block) return;

        removeById(block.items, exerciseId);

        if ((block.items?.length ?? 0) === 0) {
            block.exerciseType = null;
            block.examples = [];
        }
    };

    const addPrompt = (ex, type) => {
        ex.prompts.push({
            id: uid(),
            type: type,
            value: '',
        });
    };

    const removePrompt = (ex, promptId) => {
        removeById(ex.prompts, promptId);
    };

    const addSelectOption = (ex) => {
        ex.options.push({
            id: uid(),
            text: ''
        });
    };

    const removeSelectOption = (ex, optionId) => {
        if (ex.options.length <= 2) return;

        removeById(ex.options, optionId);

        if (ex.answerId === optionId) {
            ex.answerId = null;
        }
    };

    return {
        uid,
        provideBuilder,
        addBlock,
        removeBlock,
        moveBlock,
        getBlockEditor,
        applyChartTemplate,
        addTableColumn,
        removeTableColumn,
        addTableRow,
        removeTableRow,
        addExercise,
        removeExercise,
        addPrompt,
        removePrompt,
        addSelectOption,
        removeSelectOption
    };
}
