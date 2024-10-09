import { reactive } from 'vue';
import { useConfigStore } from './useConfigStore'; // If needed
import { useStateStore } from './useStateStore'; // If needed

export function useStepStore() {
    const configStore = useConfigStore(); // Config data, if needed
    const stateStore = useStateStore();   // State data, if needed

    const state = reactive({
        config: configStore.config,
        state: stateStore.state,
    });

    const canMovePrev = () => true;
    const canMoveNext = () => true;

    // Add any other shared logic here...

    return {
        state,
        canMovePrev,
        canMoveNext,
    };
}
