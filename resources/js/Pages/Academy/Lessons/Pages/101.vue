<script setup>
import SentenceItem from "../../../../components/SentenceItem.vue";
import {onMounted, ref} from "vue";
import LoadingSpinner from "../../../../Shared/LoadingSpinner.vue";
import BlankChartPerson from "../../../../components/Charts/BlankChartPerson.vue";
import SkillContainer from "../../../../components/SkillContainer.vue";

const sentenceIds = [1, 2, 3, 4, 5];

const sentences = ref({});
const isLoading = ref(true);

defineProps({
    lesson: Object,
})

onMounted(async () => {
    if (sentenceIds.length === 0) {
        isLoading.value = false;
        return;
    }

    try {
        const response = await axios.post(route('sentences.get-many'), {ids: sentenceIds});
        sentences.value = response.data;

    } catch (error) {
        console.error('Error fetching sentences:', error);

    } finally {
        isLoading.value = false;
    }
});
</script>
<template>
    <SkillContainer :type="lesson.skills[0].type" :title="lesson.skills[0].title">
        <p>In Arabic, the verb <b>"to be"</b> is not used in the <b>Present Tense</b>; this type of sentence is called a
            <b>nominal sentence</b>, because it has no verb. We can start using <b>subject pronouns</b> in <b>nominal
                sentences</b> to give information about ourselves & others, like this:</p>

        <div class="model-list index-list">
            <LoadingSpinner v-if="isLoading"/>
            <template v-else>
                <!--                    I am Akram-->
                <SentenceItem size="l" v-if="sentences[1]" :model="sentences[1]"/>
                <!--                    I am from Palestine-->
                <SentenceItem size="l" v-if="sentences[2]" :model="sentences[2]"/>
            </template>
        </div>

        <p>While in English there are seven distinct <b>subject pronouns</b>, in Arabic there are eight: there is no
            neutral <b>"it"</b> in Arabic, but there are distinct <b>masculine</b>, <b>feminine</b> & <b>plural</b>
            forms of <b>"you"</b>.</p>

        <BlankChartPerson :forms="{
                '1S': {
                    'ar': 'أنا',
                    'tr': 'ʔana'
                },
                '1P': {
                    'ar': 'إحنا',
                    'tr': 'ʔiħna'
                },
                '2M': {
                    'ar': 'إنتا',
                    'tr': 'ʔinta'
                },
                '2F': {
                    'ar': 'إنتي',
                    'tr': 'ʔinti'
                },
                '2P': {
                    'ar': 'إنتو',
                    'tr': 'ʔintu'
                },
                '3M': {
                    'ar': 'هو',
                    'tr': 'huwwe'
                },
                '3F': {
                    'ar': 'هي',
                    'tr': 'hiyye'
                },
                '3P': {
                    'ar': 'همّه',
                    'tr': 'humme'
                },
            }"/>
    </SkillContainer>

    <SkillContainer :type="lesson.skills[1].type" :title="lesson.skills[1].title">
        <p>In Arabic, asking questions is very easy. We ask <b>yes-or-no questions</b> by raising our intonation at
            the end
            of the sentence. When using <b>question words</b>, we may begin the sentence with them or,
            alternatively, we may
            substitute them
            for the missing piece of information.</p>

        <div class="model-list index-list">
            <LoadingSpinner v-if="isLoading"/>
            <template v-else>
                <!--                    Are you from Palestine?-->
                <SentenceItem size="l" v-if="sentences[3]" :model="sentences[3]"/>
                <!--                    Where are you from? إنتا من وين-->
                <SentenceItem size="l" v-if="sentences[4]" :model="sentences[4]"/>
                <!--                    Where are you from? من وين إنتا-->
                <SentenceItem size="l" v-if="sentences[5]" :model="sentences[5]"/>
            </template>
        </div>
    </SkillContainer>

    <SkillContainer :type="lesson.skills[2].type" :title="lesson.skills[2].title">
        <p>If you want to sound natural in Arabic, knowing common phrases is as crucial as having a broad
            vocabulary. In fact, there are greetings & sayings in Arabic for nearly every type of situation. Most of the
            time, they are returned with a specific response that is distinct from the original phrase.</p>

        <!--        <x-inflections-->
        <!--            grt="مرحبا" grttr="marħaba"-->
        <!--            rsp="مرحبتين" rsptr="marħabtēn"-->
        <!--        ></x-inflections>-->

        <p>In the case of certain common greetings — like <b>"hello"</b> — you can get away with responding with the
            original phrase, but this is not always the case. Plus, it's unnatural to answer most well-wishes — like
            <b>"welcome"</b> — with a mere <b>"thank you"</b>; it's customary to use a specific response to return the
            intention. It's never too early to get in the habit of learning Arabic phrases with their proper responses.
        </p>

        <!--        <x-inflections-->
        <!--            grt="أهلا وسهلا" grttr="ʔahla w-sahla"-->
        <!--            rsp="أهلين" rsptr="ʔahlēn"-->
        <!--        ></x-inflections>-->

        <p>Because of cultural differences, the literal meanings of these phrases don't necessarily tell you everything
            about the situations they're used in, so pay attention to how they're used in the dialogues! In English, for
            instance, <b>"welcome"</b> is a slightly formal greeting, most often
            used to invite someone into a space. In Arabic, though, <b>أهلا وسهلا (ʔahla w-sahla)</b> is a very
            mundane greeting that is often used as a more effusive <b>"hello"</b> or to greet someone from a different
            country or city.</p>

        <!--        <x-inflections-->
        <!--            grt="تشرّفنا" grttr="tšarrafna"-->
        <!--            rsp="إلي الشرف" rsptr="ʔily š-šaraf"-->
        <!--        ></x-inflections>-->
    </SkillContainer>

    <!--<x-lesson-concept section-type="idea" section-title="speak like a native"-->
    <!--                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">-->
    <!--</x-lesson-concept>-->

    <!--<x-activity-area title="{{ __('exercises') }}">-->
    <!--    <p>Consider the following information about some students at Birzeit University & use it to complete the-->
    <!--        following exercises. Although these students are all Palestinian, not all of them were born & raised there.-->
    <!--        Get-->
    <!--        acquainted with their names, as you will learn more about them & their colleagues throughout the-->
    <!--        curriculum.</p>-->
    <!--    <div class="array">-->
    <!--        <div class="activity-info-container">-->
    <!--            <div>NAME</div>-->
    <!--            <div>COUNTRY</div>-->

    <!--            <div>جريس</div>-->
    <!--            <div>فلسطين</div>-->

    <!--            <div>سما</div>-->
    <!--            <div>فلسطين</div>-->

    <!--            <div>محمّد</div>-->
    <!--            <div>الأردن</div>-->

    <!--            <div>يوسف</div>-->
    <!--            <div>سوريا</div>-->

    <!--            <div>لمى</div>-->
    <!--            <div>لبنان</div>-->

    <!--            <div>أروى</div>-->
    <!--            <div>مصر</div>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <p>{{ __('activity.yes-no') }}</p>-->
    <!--    <div class="question-group shuffle">-->
    <!--        <x-activity-mc que="جريس من فلسطين؟" ans="a"-->
    <!--                       a="آه"-->
    <!--                       b="لأ"-->
    <!--        />-->
    <!--        <x-activity-mc que="أروى من سوريا؟" ans="b"-->
    <!--                       a="آه"-->
    <!--                       b="لأ"-->
    <!--        />-->
    <!--        <x-activity-mc que="محمّد من الأردن؟" ans="a"-->
    <!--                       a="آه"-->
    <!--                       b="لأ"-->
    <!--        />-->
    <!--        <x-activity-mc que="لمى من مصر؟" ans="b"-->
    <!--                       a="آه"-->
    <!--                       b="لأ"-->
    <!--        />-->
    <!--        <x-activity-mc que="سما من لبنان؟" ans="b"-->
    <!--                       a="آه"-->
    <!--                       b="لأ"-->
    <!--        />-->
    <!--        <x-activity-mc que="يوسف من سوريا؟" ans="a"-->
    <!--                       a="آه"-->
    <!--                       b="لأ"-->
    <!--        />-->
    <!--    </div>-->

    <!--    <p>{{ __('activity.complete-sentence') }}</p>-->
    <!--    <div class="dialog-body">-->
    <!--        <x-dialog-line speaker="سؤال" arb="من وين محمّد؟" eng="where is Mhammad from?"/>-->
    <!--        <x-dialog-line speaker="جواب" arb="محمّد من الأردن" eng="Mhammad is from Jordan"/>-->
    <!--    </div>-->

    <!--    <div class="question-group shuffle">-->
    <!--        <x-activity-fill-->
    <!--            que="من وين يوسف؟"-->
    <!--            ans="يوسف من سوريا"/>-->
    <!--        <x-activity-fill-->
    <!--            que="أروى من وين؟"-->
    <!--            ans="أروى من مصر"/>-->
    <!--        <x-activity-fill-->
    <!--            que="من وين جريس وسما؟"-->
    <!--            ans="جريس وسما من فلسطين"/>-->
    <!--        <x-activity-fill-->
    <!--            que="محمّد ولمى من وين؟"-->
    <!--            ans="محمّد من الأردن ولمى من لبنان"/>-->

    <!--        <x-activity-fill-->
    <!--            que="مين من مصر؟"-->
    <!--            ans="أروى من مصر"/>-->
    <!--        <x-activity-fill-->
    <!--            que="مين من الأردن؟"-->
    <!--            ans="محمّد من الأردن"/>-->
    <!--        <x-activity-fill-->
    <!--            que="مين من لبنان؟"-->
    <!--            ans="لمى من لبنان"/>-->
    <!--        <x-activity-fill-->
    <!--            que="مين من فلسطين؟"-->
    <!--            ans="جريس وسما من فلسطين"/>-->
    <!--    </div>-->
    <!--</x-activity-area>-->

    <!--<x-activity-area title="{{ __('dialogue') }}">-->
    <!--    <p>Shadow the following conversation between Jeries & Lama. It's the start of the new semester at Birzeit-->
    <!--        University, so students are getting to know each other.</p>-->
    <!--    <div class="dialog-body">-->
    <!--        <x-dialog-line speaker="جريس" arb="مرحبا، أنا جريس"/>-->
    <!--        <x-dialog-line speaker="لمى" arb="مرحبتين، أنا لمى، تشرّفنا"/>-->
    <!--        <x-dialog-line speaker="جريس" arb="إلي الشرف — إنتي من فلسطين؟"/>-->
    <!--        <x-dialog-line speaker="لمى" arb="لأ، أنا من لبنان"/>-->
    <!--        <x-dialog-line speaker="جريس" arb="أهلا وسهلا"/>-->
    <!--        <x-dialog-line speaker="لمى" arb="أهلين أهلين — وإنتا من وين؟"/>-->
    <!--        <x-dialog-line speaker="جريس" arb="أنا من القدس — يعني من فلسطين"/>-->
    <!--    </div>-->

    <!--    <x-activity-mc que="مين من لبنان؟" ans="b" shuffle-->
    <!--                   a="جريس"-->
    <!--                   b="لمى"-->
    <!--    />-->
    <!--    <x-activity-mc que="جريس من وين؟" ans="c" shuffle-->
    <!--                   a="الأردن"-->
    <!--                   b="سوريا"-->
    <!--                   c="فلسطين"-->
    <!--                   d="مصر"-->
    <!--    />-->

    <!--    <p>Use the template to introduce yourself to someone:</p>-->
    <!--    <div class="dialog-body">-->
    <!--        <x-dialog-line speaker="جريس" arb="مرحبا، أنا ________"/>-->
    <!--        <x-dialog-line speaker="لمى" arb="مرحبتين ________، أنا ________"/>-->
    <!--        <x-dialog-line speaker="جريس" arb="تشرّفنا — من وين إنتي؟"/>-->
    <!--        <x-dialog-line speaker="لمى" arb="إلي الشرف — أنا من ________، وإنتا؟"/>-->
    <!--        <x-dialog-line speaker="جريس" arb="أنا من ________"/>-->
    <!--        <x-dialog-line speaker="لمى" arb="أهلا وسهلا"/>-->
    <!--        <x-dialog-line speaker="جريس" arb="أهلين"/>-->
    <!--    </div>-->
    <!--</x-activity-area>-->
</template>
