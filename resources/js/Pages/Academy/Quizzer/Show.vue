<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {useQuizzerStore} from "./Stores/QuizzerStore.js";
import Setup from "./Pages/Setup.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onMounted} from "vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    quizType: String,
    model: Object,
});

const QuizzerStore = useQuizzerStore();

onMounted(() => {
    QuizzerStore.reset();
    QuizzerStore.data.quizType = props.quizType;
    QuizzerStore.data.model = props.model;
});
</script>
<template>
    <Head :title="`Academy: Quizzer (${quizType})`"/>
    <div id="app-body">
        <div class="rw-container window-container">
            <div class="window-header">
                <Link :href="route('quizzer.index')" class="material-symbols-rounded">arrow_back</Link>
                <div class="window-header-url">www.palweb.app/academy/quizzer/{{quizType}}/{deck}</div>
            </div>
            <div class="window-section-head">
                <h1>Quizzer</h1>
            </div>
            <div class="window-page-nav" style="grid-template-columns: min-content 1fr 1fr 1fr min-content">
                <button class="material-symbols-rounded">arrow_back</button>
                <div class="material-symbols-rounded" :class="{ active: QuizzerStore.data.step === 'setup' }">
                    settings
                </div>
                <div class="material-symbols-rounded" :class="{ active: QuizzerStore.data.step === 'quiz' }">
                    assignment
                </div>
                <div class="material-symbols-rounded" :class="{ active: QuizzerStore.data.step === 'results' }">
                    assignment_turned_in
                </div>
                <button class="material-symbols-rounded">arrow_forward</button>
            </div>
            <Setup v-if="QuizzerStore.data.step ==='setup'"/>
            <Quiz v-if="QuizzerStore.data.step ==='quiz'"/>
            <Results v-if="QuizzerStore.data.step ==='results'"/>
        </div>
    </div>
</template>
