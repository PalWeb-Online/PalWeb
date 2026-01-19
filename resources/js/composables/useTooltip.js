import {ref} from "vue";

export function useTooltip() {
    const message = ref("");
    const isVisible = ref(false);
    const tooltipData = ref(null);
    const tooltipStyle = ref({
        top: '0px',
        left: '0px',
    });

    const showTooltip = (data, event) => {
        if (typeof data === 'string') {
            message.value = data;
            tooltipData.value = null;

        } else {
            tooltipData.value = data;
            message.value = "";
        }

        isVisible.value = true;
        updatePosition(event);
    };

    const hideTooltip = () => isVisible.value = false;

    const updatePosition = (event) => {
        tooltipStyle.value = {
            top: `${event.clientY + 10}px`,
            left: `${event.clientX + 10}px`,
        };
    };

    return {
        message,
        isVisible,
        tooltipData,
        tooltipStyle,
        showTooltip,
        hideTooltip,
    };
}
