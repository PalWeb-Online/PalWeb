import { useStateStore } from './useStateStore';

export function useSidebarStore() {
    const stateStore = useStateStore(); // Reuse the state store

    return {
        state: stateStore.state, // Expose the state for the sidebar to use
    };
}
