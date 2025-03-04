import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Howl} from "howler";

export function useTerm(props) {
    const data = reactive({
        term: {},
        isLoading: true,
    });

    const audio = ref(null);

    const loadAudio = () => {
        if (data.term.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${data.term.audio}`],
            });
        }
    }

    const playAudio = () => {
        if (audio.value) {
            audio.value.play();
        }
    }

    const fetchTerm = async () => {
        if (props.model) {
            data.term = props.model;

        } else {
            try {
                const response = await axios.get(route('terms.get', props.id));
                data.term = response.data.data;


            } catch (error) {
                console.error('Error fetching Term:', error);
            }
        }

        if (props.glossId) {
            data.term.gloss = data.term.glosses.find((gloss) => gloss.id === props.glossId);
        }

        loadAudio();
        data.isLoading = false;
    }

    const fetchPronunciations = async () => {
        try {
            const response = await axios.get(route('terms.get.pronunciations', {term: data.term.id}));
            const pronunciations = response.data.filter(pronunciation =>
                !data.term.pronunciations.some(existing => existing.id === pronunciation.id)
            );

            data.term.pronunciations.push(...pronunciations);

        } catch (error) {
            console.error('Error fetching Pronunciations:', error);
        }
    }

    const fetchSentences = async (glossId) => {
        try {
            const gloss = data.term.glosses.find(gloss => gloss.id === glossId);

            const response = await axios.get(route('terms.get.sentences', {term: data.term.id, gloss: glossId}));
            const sentences = response.data.filter(sentence =>
                !gloss.sentences.some(existing => existing.id === sentence.id)
            );

            gloss.sentences.push(...sentences);

        } catch (error) {
            console.error('Error fetching Pronunciations:', error);
        }
    }

    onMounted(fetchTerm);

    return {data, playAudio, fetchPronunciations, fetchSentences};
}
