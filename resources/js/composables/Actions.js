import {nextTick, onBeforeUnmount, ref, watch} from "vue";
import {flip, offset, shift, useFloating} from "@floating-ui/vue";

export function useActions() {
    const isOpen = ref(false);
    const reference = ref(null);
    const floating = ref(null);
    const openedByKeyboard = ref(false);

    const {floatingStyles} = useFloating(reference, floating, {
        placement: 'bottom',
        middleware: [offset(4), flip(), shift()],
    });

    const toggleMenu = (isKeyboard = false) => {
        isOpen.value = !isOpen.value;
        openedByKeyboard.value = isKeyboard;
    }

    function closeMenu() {
        isOpen.value = false;
        reference.value?.focus();
        openedByKeyboard.value = false;
    }

    function onMenuKeydown(e) {
        const items = Array.from(floating.value.querySelectorAll('[role=menuitem]'));
        const idx = items.indexOf(document.activeElement);

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                items[(idx + 1) % items.length].focus();
                break;

            case 'ArrowUp':
                e.preventDefault();
                items[(idx - 1 + items.length) % items.length].focus();
                break;

            case 'Tab':
                e.preventDefault();
                if (e.shiftKey) {
                    items[(idx - 1 + items.length) % items.length].focus();
                } else {
                    items[(idx + 1) % items.length].focus();
                }
                break;

            case 'Escape':
                e.preventDefault();
                closeMenu();
                break;
        }
    }

    function onOutsideClick(e) {
        if (
            floating.value &&
            !floating.value.contains(e.target) &&
            !reference.value.contains(e.target)
        ) {
            closeMenu();
        }
    }

    watch(isOpen, async open => {
        if (open) {
            await nextTick();
            if (openedByKeyboard.value) floating.value.querySelector('[role=menuitem]').focus({ preventScroll: true });
            document.addEventListener('click', onOutsideClick);

        } else {
            document.removeEventListener('click', onOutsideClick);
            openedByKeyboard.value = false;
        }
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', onOutsideClick);
    });

    return {toggleMenu, onMenuKeydown, floatingStyles, isOpen, reference, floating, closeMenu};
}
