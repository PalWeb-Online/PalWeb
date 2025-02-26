import {onMounted, onBeforeUnmount} from 'vue';
import {useSearchStore} from '../stores/SearchStore.js';

export function useSearchGenie(action) {
    const SearchStore = useSearchStore();

    onMounted(() => {
        typeof action === 'function'
            ? SearchStore.setAction(action())
            : SearchStore.setAction(action);
    });

    onBeforeUnmount(() => {
        SearchStore.resetAction();
    });
}
