<script setup>
import {computed, nextTick, onBeforeUnmount, onMounted, ref, watch} from "vue";

const props = defineProps({
    gap: {
        type: String,
        default: "3.2rem",
    },
    speed: {
        type: Number,
        default: 36,
    },
    direction: {
        type: String,
        default: "left",
        validator: (value) => ["left", "right"].includes(value),
    },
    pauseOnHover: {
        type: Boolean,
        default: true,
    },
    resumeDelay: {
        type: Number,
        default: 900,
    },
});

const viewport = ref(null);
const contentSet = ref(null);
const duplicateContentSet = ref(null);

const offset = ref(0);
const setWidth = ref(0);
const isHovering = ref(false);
const isPointerDown = ref(false);
const isDragging = ref(false);
const hasDragged = ref(false);

let animationFrame = null;
let lastTimestamp = null;
let resumeTimeout = null;
let resizeObserver = null;
let isInteractionScrollLocked = false;
let dragStartTimeout = null;

let pointerStartX = 0;
let pointerStartY = 0;
let pointerStartOffset = 0;

const dragThreshold = 8;

const signedSpeed = computed(() => {
    return props.direction === "left" ? -props.speed : props.speed;
});

const shouldAutoScroll = computed(() => {
    if (isPointerDown.value || isDragging.value) {
        return false;
    }

    return !(props.pauseOnHover && isHovering.value);
});

const trackStyle = computed(() => ({
    transform: `translate3d(${offset.value}px, 0, 0)`,
    gap: props.gap,
}));

const setStyle = computed(() => ({
    gap: props.gap,
}));

const normalizeOffset = () => {
    if (!setWidth.value) {
        return;
    }

    const width = setWidth.value;

    while (offset.value <= -width) {
        offset.value += width;
    }

    while (offset.value > 0) {
        offset.value -= width;
    }
};

const measure = async () => {
    await nextTick();

    if (!contentSet.value) {
        return;
    }

    if (duplicateContentSet.value) {
        const firstSetRect = contentSet.value.getBoundingClientRect();
        const duplicateSetRect = duplicateContentSet.value.getBoundingClientRect();

        setWidth.value = duplicateSetRect.left - firstSetRect.left;

    } else {
        setWidth.value = contentSet.value.getBoundingClientRect().width;
    }

    normalizeOffset();
};

const stopResumeTimer = () => {
    if (resumeTimeout) {
        clearTimeout(resumeTimeout);
        resumeTimeout = null;
    }
};

const stopDragStartTimer = () => {
    if (dragStartTimeout) {
        clearTimeout(dragStartTimeout);
        dragStartTimeout = null;
    }
};

const startDragging = (event) => {
    if (!isPointerDown.value || isDragging.value) {
        return;
    }

    lockInteractionScroll();

    isDragging.value = true;
    hasDragged.value = true;
    isHovering.value = true;

    viewport.value?.setPointerCapture?.(event.pointerId);
};

const preventInteractionScroll = (event) => {
    event.preventDefault();
};

const lockInteractionScroll = () => {
    if (isInteractionScrollLocked) {
        return;
    }

    isInteractionScrollLocked = true;

    window.addEventListener("wheel", preventInteractionScroll, {passive: false, capture: true});
    window.addEventListener("touchmove", preventInteractionScroll, {passive: false, capture: true});
};

const unlockInteractionScroll = () => {
    if (!isInteractionScrollLocked) {
        return;
    }

    isInteractionScrollLocked = false;

    window.removeEventListener("wheel", preventInteractionScroll, {capture: true});
    window.removeEventListener("touchmove", preventInteractionScroll, {capture: true});
};

const delayResume = () => {
    stopResumeTimer();

    if (!props.pauseOnHover) {
        return;
    }

    isHovering.value = true;

    resumeTimeout = setTimeout(() => {
        if (!isDragging.value) {
            isHovering.value = false;
        }
    }, props.resumeDelay);
};

const tick = (timestamp) => {
    if (lastTimestamp === null) {
        lastTimestamp = timestamp;
    }

    const deltaSeconds = (timestamp - lastTimestamp) / 1000;
    lastTimestamp = timestamp;

    if (shouldAutoScroll.value && setWidth.value > 0) {
        offset.value += signedSpeed.value * deltaSeconds;
        normalizeOffset();
    }

    animationFrame = requestAnimationFrame(tick);
};

const startAnimation = () => {
    if (animationFrame) {
        return;
    }

    lastTimestamp = null;
    animationFrame = requestAnimationFrame(tick);
};

const stopAnimation = () => {
    if (animationFrame) {
        cancelAnimationFrame(animationFrame);
        animationFrame = null;
    }

    lastTimestamp = null;
};

const onPointerEnter = () => {
    if (props.pauseOnHover) {
        isHovering.value = true;
    }
};

const onPointerLeave = () => {
    if (!isDragging.value) {
        isHovering.value = false;
    }
};

const onPointerDown = (event) => {
    if (!viewport.value) {
        return;
    }

    if (event.pointerType === "mouse" && event.button !== 0) {
        return;
    }

    stopResumeTimer();
    stopDragStartTimer();

    isPointerDown.value = true;
    isDragging.value = false;
    hasDragged.value = false;
    isHovering.value = true;

    pointerStartX = event.clientX;
    pointerStartY = event.clientY;
    pointerStartOffset = offset.value;
};

const onPointerMove = (event) => {
    if (!isPointerDown.value) {
        return;
    }

    const deltaX = event.clientX - pointerStartX;
    const deltaY = event.clientY - pointerStartY;

    if (!isDragging.value) {
        if (Math.abs(deltaX) < dragThreshold || Math.abs(deltaX) <= Math.abs(deltaY)) return;
        stopDragStartTimer();
        startDragging(event);
    }

    offset.value = pointerStartOffset + deltaX;
    normalizeOffset();

    event.preventDefault();
};

const onPointerUp = (event) => {
    if (!isPointerDown.value) {
        return;
    }

    const wasDragging = isDragging.value;

    isPointerDown.value = false;
    isDragging.value = false;
    stopDragStartTimer();
    unlockInteractionScroll();

    if (wasDragging) {
        viewport.value?.releasePointerCapture?.(event.pointerId);
        delayResume();
    }

    window.setTimeout(() => {
        hasDragged.value = false;
    }, 0);
};

const onDragStart = (event) => {
    event.preventDefault();
};

const onFocusIn = () => {
    if (props.pauseOnHover) {
        isHovering.value = true;
    }
};

const onFocusOut = () => {
    isHovering.value = false;
};

const onClickCapture = (event) => {
    if (hasDragged.value) {
        event.preventDefault();
        event.stopPropagation();
    }
};

watch(
    () => [props.gap],
    () => measure()
);

onMounted(async () => {
    await measure();

    resizeObserver = new ResizeObserver(() => {
        measure();
    });

    if (contentSet.value) {
        resizeObserver.observe(contentSet.value);
    }

    startAnimation();
});

onBeforeUnmount(() => {
    stopAnimation();
    stopResumeTimer();
    stopDragStartTimer();
    unlockInteractionScroll();

    if (resizeObserver) {
        resizeObserver.disconnect();
        resizeObserver = null;
    }
});
</script>

<template>
    <div
        ref="viewport"
        class="infinite-carousel"
        :class="{
            'is-dragging': isDragging,
            'is-paused': !shouldAutoScroll,
        }"
        @pointerenter="onPointerEnter"
        @pointerleave="onPointerLeave"
        @pointerdown="onPointerDown"
        @pointermove="onPointerMove"
        @pointerup="onPointerUp"
        @pointercancel="onPointerUp"
        @focusin="onFocusIn"
        @focusout="onFocusOut"
        @click.capture="onClickCapture"
        @dragstart.capture="onDragStart"
    >
        <div class="infinite-carousel-track" :style="trackStyle">
            <div
                ref="contentSet"
                class="infinite-carousel-set"
                :style="setStyle"
            >
                <slot/>
            </div>

            <div
                ref="duplicateContentSet"
                class="infinite-carousel-set"
                :style="setStyle"
                aria-hidden="true"
            >
                <slot/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.infinite-carousel {
    max-width: 100vw;
    overflow: hidden;
    padding-block: 1.6rem;
    cursor: grab;
    touch-action: pan-y;
    user-select: none;
    -webkit-user-select: none;

    &.is-dragging {
        cursor: grabbing;
    }

    :deep(img) {
        user-drag: none;
        -webkit-user-drag: none;
        user-select: none;
        -webkit-user-select: none;
    }

    :deep(a),
    :deep(button),
    :deep(input),
    :deep(select),
    :deep(textarea),
    :deep([role="button"]) {
        pointer-events: auto;
    }
}

.infinite-carousel-track {
    display: flex;
    flex-flow: row nowrap;
    align-items: flex-start;
    width: max-content;
    will-change: transform;
}

.infinite-carousel-set {
    display: flex;
    flex-flow: row nowrap;
    align-items: flex-start;
    flex: 0 0 auto;

    > :deep(*) {
        flex: 0 0 auto;
    }
}
</style>
