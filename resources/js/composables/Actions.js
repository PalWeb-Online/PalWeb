import {onBeforeUnmount, ref} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

export function useActions() {
    const isOpen = ref(false);
    const reference = ref(null);
    const floating = ref(null);

    const {floatingStyles} = useFloating(reference, floating, {
        placement: 'bottom',
        middleware: [offset(4), flip(), shift()],
    });

    const toggleMenu = () => {
        isOpen.value = !isOpen.value;
        if (isOpen.value) {
            document.addEventListener('click', handleClickOutside);
        } else {
            document.removeEventListener('click', handleClickOutside);
        }
    };

    const handleClickOutside = (event) => {
        if (!reference.value.contains(event.target) && !floating.value.contains(event.target)) {
            isOpen.value = false;
            document.removeEventListener('click', handleClickOutside);
        }
    };

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside);
    });

    return { toggleMenu, floatingStyles, isOpen, reference, floating };
}
