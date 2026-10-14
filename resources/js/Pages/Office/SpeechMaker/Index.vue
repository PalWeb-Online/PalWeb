<script setup>
import Layout from "../../../Shared/Layout.vue";
import DialogItem from "../../../components/DialogItem.vue";
import TermItem from "../../../components/TermItem.vue";
import {onMounted, ref} from "vue";
import AppTip from "../../../components/AppTip.vue";
import {route} from "ziggy-js";
import {useRouter} from "vue-router";

defineOptions({
    layout: Layout
});

const mode = ref('dialog');
const router = useRouter();
const dialogs = ref([]);
const terms = ref([]);

onMounted(async () => {
    const {data} = await axios.get(route('speech-maker.get'));
    dialogs.value = data.dialogs?.data ?? data.dialogs ?? [];
    terms.value = data.terms?.data ?? data.terms ?? [];
});

const toBuild = () => {
    mode.value === 'dialog'
        ? router.push({name: 'speech-maker.dialog'})
        : router.push({name: 'speech-maker.sentence'});
}
</script>

<template>
    <Head title="Speech Maker"/>

    <div id="app-head">
        <h1>{{ $t('pages.speech-maker.title') }}</h1>
    </div>
    <div id="app-body">
        <div class="app-body-section">
            <div class="sm-mode-select">
                <button class="featured-title l" :class="{'active': mode === 'dialog'}"
                        @click="mode = 'dialog'">dlg
                </button>
                <div :class="mode">
                    <button class="material-symbols-rounded" @click="toBuild()">
                        add
                    </button>
                </div>
                <button class="featured-title l" :class="{'active': mode === 'sentence'}"
                        @click="mode = 'sentence'">snt
                </button>
            </div>

            <div v-if="mode === 'dialog'" class="model-list">
                <DialogItem v-for="dialog in dialogs" :model="dialog" target="workbench"/>
            </div>
            <div v-else-if="mode === 'sentence'" class="model-list">
                <AppTip>
                    <p>Need some inspiration? Here are 25 random Terms that have no Sentences for one of their
                        Glosses. (The Controller could be adjusted so that the TermItem specifies the Gloss that doesn't
                        have a Sentence, as right now the default Gloss is shown.)</p>
                </AppTip>
                <TermItem v-for="term in terms" :model="term"/>
            </div>
        </div>
    </div>
</template>
