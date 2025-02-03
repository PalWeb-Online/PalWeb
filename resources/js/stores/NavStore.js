import {defineStore} from 'pinia';
import {reactive} from "vue";

export const useNavigationStore = defineStore('NavigationStore', () => {
    const data = reactive({
        section: 'home',
    });

    return {
        data
    };
});
