<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";

const QuizzerStore = useQuizzerStore();
</script>
<template>
    <div style="background: white">
        You got a {{QuizzerStore.score}}/{{QuizzerStore.quiz.length}}.
    </div>
</template>
