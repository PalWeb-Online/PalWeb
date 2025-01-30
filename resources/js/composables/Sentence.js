import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Howl} from "howler";

export function useSentence(props) {
    const data = reactive({
        sentence: {},
        isLoading: true
    });

    const audio = ref(null);

    function loadAudio() {
        if (data.sentence.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${data.sentence.audio}`],
            });
        }
    }

    function playAudio() {
        if (audio.value) {
            audio.value.play();
        }
    }

    async function fetchSentence() {
        if (props.model) {
            data.sentence = props.model;
            loadAudio();
            data.isLoading = false;

        } else {
            try {
                const response = await axios.get(route('sentences.get', props.id));
                data.sentence = response.data.data;
                loadAudio();
                data.isLoading = false;

            } catch (error) {
                console.error("Error fetching Sentence:", error);
            }
        }
    }

    const isCurrentTerm = (term) => {
        return term.id === props.currentTerm;
    }

    onMounted(fetchSentence);

    return { data, isCurrentTerm, playAudio };
}
