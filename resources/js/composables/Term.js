import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Howl} from "howler";

export function useTerm(props) {
    const data = reactive({
        term: {},
        isLoading: true,
    });

    const audio = ref(null);

    function playAudio() {
        if (audio.value) {
            audio.value.play();
        }
    }

    async function fetchTerm() {
        try {
            const response = await axios.get(route('terms.get', props.id));
            data.term = response.data.term;

            if (props.glossId) {
                data.term.gloss = data.term.glosses.find((gloss) => gloss.id === props.glossId);
            }

            if (data.term.audio) {
                audio.value = new Howl({
                    src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${data.term.audio}`],
                });
            }

            data.isLoading = false;

        } catch (error) {
            console.error('Error fetching Term:', error);
        }
    }

    onMounted(fetchTerm);

    return { data, playAudio };
}
