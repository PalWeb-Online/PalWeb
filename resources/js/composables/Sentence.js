import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Howl} from "howler";

export function useSentence(props) {
    const data = reactive({
        sentence: {},
        isLoading: true
    });

    const audio = ref(null);

    const loadAudio = () => {
        if (data.sentence.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${data.sentence.audio}`],
            });
        }
    }

    const playAudio = () => {
        if (audio.value) {
            audio.value.play();
        }
    }

    const fetchSentence = async () => {
        if (props.model) {
            data.sentence = props.model;

        } else {
            try {
                const response = await axios.get(route('sentences.get', props.id));
                data.sentence = response.data.data;

            } catch (error) {
                console.error("Error fetching Sentence:", error);
            }
        }

        loadAudio();
        data.isLoading = false;
    }

    const isCurrentTerm = (term) => {
        return term.id === props.currentTerm;
    }

    onMounted(fetchSentence);

    return {data, isCurrentTerm, playAudio};
}
