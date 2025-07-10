import {onMounted, reactive, ref, watch} from "vue";
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

    onMounted(() => {
        Object.assign(term, props.model);

        if (term.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${term.audio}`],
            });
        }

        isLoading.value = false;
    });

    watch(() => props.model,
        (newTerm) => {
            Object.assign(term, newTerm);

            if (term.audio) {
                audio.value = new Howl({
                    src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${term.audio}`],
                });
            }
        },
        {deep: true}
    );

    return {term, isLoading, playAudio};
}
