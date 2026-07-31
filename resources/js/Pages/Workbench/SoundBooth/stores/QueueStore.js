import {defineStore} from 'pinia';
import {reactive, ref, watch} from 'vue';
import {useSoundBoothStore} from "./SoundBoothStore.js";
import {useRecordStore} from './RecordStore';
import {route} from "ziggy-js";
import {useNotificationStore} from "../../../../stores/NotificationStore.js";
import {useI18n} from "vue-i18n";

export const useQueueStore = defineStore('QueueStore', () => {
    const {t} = useI18n();
    const SoundBoothStore = useSoundBoothStore();
    const RecordStore = useRecordStore();

    const NotificationStore = useNotificationStore();

    const queue = reactive([]);

    const selected = ref(0);
    const selectedArray = reactive([]);

    watch(selected, (newIndex) => {
        const container = document.querySelector('section:first-child');
        const selectedItem = container?.querySelectorAll('.rw-record-queue > div')[newIndex];

        if (selectedItem && container) {
            const itemOffsetTop = selectedItem.offsetTop;
            const itemHeight = selectedItem.offsetHeight;
            const containerHeight = container.offsetHeight;

            const scrollPosition = itemOffsetTop - (containerHeight / 2) + (itemHeight / 2);
            container.scrollTo({
                top: scrollPosition,
                behavior: 'smooth',
            });
        }
    });

    const initSelection = () => {
        selectedArray.splice(0, selectedArray.length);
        for (let i = 0; i < queue.length; i++) {
            selectedArray.push(false);
        }

        for (let i = 0; i < queue.length; i++) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                break;
            }
        }
    };

    const selectItem = (index) => {
        selectedArray[selected.value] = false;
        selected.value = index;
        selectedArray[index] = 'selected';

        const newSelected = queue[index];
        if (RecordStore.data.status[newSelected.id] === 'stashed') {
            if (SoundBoothStore.data.isRecording) RecordStore.stopRecording();
            RecordStore.playRecord();
        }
    };

    const moveBackward = () => {
        for (let i = selected.value - 1; i >= 0; i--) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const moveForward = () => {
        for (let i = selected.value + 1; i < queue.length; i++) {
            if (isSelectable(queue[i])) {
                selectItem(i);
                return true;
            }
        }
        return false;
    };

    const isSelectable = (element) => {
        return !['up', 'ready', 'stashing'].includes(RecordStore.data.status[element]);
    }

    const fetchAutoItems = async () => {
        try {
            const {data} = await axios.post(route('sound-booth.get.auto'), {
                speaker_id: SoundBoothStore.speaker.id,
                dialect_id: SoundBoothStore.speaker.dialect.id,
                queuedItems: queue,
            });

            queue.push(...data.items);
            NotificationStore.addNotification(t('sound-booth.notifications.auto-queue-success', {count: data.items.length}));

        } catch (e) {
            console.error(t('sound-booth.notifications.auto-queue-error'), e);
            NotificationStore.addNotification(t('sound-booth.notifications.auto-queue-error'));
        }
    };

    const fetchDeckItems = async (id) => {
        try {
            const {data} = await axios.post(route('sound-booth.get.deck', id), {
                speaker_id: SoundBoothStore.speaker.id,
                dialect_id: SoundBoothStore.speaker.dialect.id,
                queuedItems: queue,
            });

            queue.push(...data.items);
            NotificationStore.addNotification(t('sound-booth.notifications.deck-queue-success', {count: data.items.length}));

        } catch (e) {
            console.error(t('sound-booth.notifications.deck-queue-error'), e);
            NotificationStore.addNotification(t('sound-booth.notifications.deck-queue-error'));
        }
    };

    const removeItem = async (pronunciation) => {
        const index = queue.indexOf(pronunciation);

        if (RecordStore.data.records[pronunciation.id]) {
            if (!confirm(t('sound-booth.notifications.remove-item-confirm'))) return false;

            const discarded = await RecordStore.discardRecord(pronunciation.id);
            if (!discarded) return false;
        }

        queue.splice(index, 1);
        return true;
    };

    const flushQueue = async () => {
        if (queue.length === 0 || confirm(t('sound-booth.notifications.flush-queue-confirm'))) {
            queue.splice(0, queue.length);

            await RecordStore.clearStash();
            return true;
        }
        return false;
    };

    return {
        queue,
        selected,
        selectedArray,
        initSelection,
        selectItem,
        moveBackward,
        moveForward,
        isSelectable,
        fetchDeckItems,
        fetchAutoItems,
        removeItem,
        flushQueue,
    };
});
