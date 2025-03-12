import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Howl} from "howler";

export function useTerm(props) {
    const term = reactive({});
    const audio = ref(null);
    const isLoading = ref(true);

    const playAudio = () => {
        if (audio.value) {
            audio.value.play();
        }
    }

    const fetchPronunciations = async () => {
        try {
            const response = await axios.get(route('terms.get.pronunciations', {term: term.id}));
            const pronunciations = response.data.filter(pronunciation =>
                !term.pronunciations.some(existing => existing.id === pronunciation.id)
            );

            term.pronunciations.push(...pronunciations);

        } catch (error) {
            console.error('Error fetching Pronunciations:', error);
        }
    }

    const fetchSentences = async (glossId) => {
        try {
            const gloss = term.glosses.find(gloss => gloss.id === glossId);

            const response = await axios.get(route('terms.get.sentences', {term: term.id, gloss: glossId}));
            const sentences = response.data.filter(sentence =>
                !gloss.sentences.some(existing => existing.id === sentence.id)
            );

            gloss.sentences.push(...sentences);

        } catch (error) {
            console.error('Error fetching Pronunciations:', error);
        }
    }

    onMounted(() => {
        Object.assign(term, props.model);

        if (term.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${term.audio}`],
            });
        }

        isLoading.value = false;
    });

    return {term, isLoading, playAudio, fetchPronunciations, fetchSentences};
}
