import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";
import {Howl} from "howler";

export function useTerm(props) {
    const data = reactive({
        term: {},
        isLoading: true,
    });

    const audio = ref(null);

    function loadAudio() {
        if (data.term.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${data.term.audio}`],
            });
        }
    }

    function playAudio() {
        if (audio.value) {
            audio.value.play();
        }
    }

    async function fetchTerm() {
        if (props.model) {
            data.term = props.model;
            loadAudio();
            data.isLoading = false;

        } else {
            try {
                const response = await axios.get(route('terms.get', props.id));
                data.term = response.data.data;

                if (props.glossId) {
                    data.term.gloss = data.term.glosses.find((gloss) => gloss.id === props.glossId);
                }

                loadAudio();
                data.isLoading = false;

            } catch (error) {
                console.error('Error fetching Term:', error);
            }
        }
    }

    onMounted(fetchTerm);

    return { data, playAudio };
}
