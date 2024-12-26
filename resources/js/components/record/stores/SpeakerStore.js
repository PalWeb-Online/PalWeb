import {defineStore} from 'pinia';
import {reactive} from 'vue';
import axios from 'axios';

export const useSpeakerStore = defineStore('SpeakerStore', () => {
    const data = reactive({
        speaker: {
            id: '',
            name: '',
            avatar: '',
            user_id: null,
            dialect_id: null,
            location_id: null,
            fluency: '',
            gender: '',
            exists: false,
        },
    });

    const fetchSpeaker = async () => {
        if (!data.speaker.user_id) {
            try {
                const response = await axios.get('/record/speaker');
                if (response.data) {
                    Object.assign(data.speaker, response.data.speaker, {
                        name: response.data.name,
                        avatar: response.data.avatar,
                        exists: response.data.exists,
                    });
                }
            } catch (error) {
                console.error('Error loading speaker:', error);
            }
        }
    };

    const saveSpeaker = async () => {
        try {
            const response = await axios.post('/record/speaker', {
                user_id: data.speaker.user_id,
                dialect_id: data.speaker.dialect_id,
                location_id: data.speaker.location_id,
                fluency: data.speaker.fluency,
                gender: data.speaker.gender,
            });

            Object.assign(data.speaker, response.data.speaker, {
                name: response.data.name,
                avatar: response.data.avatar,
                exists: true,
            });
            alert(response.data.message);

            return true;

        } catch (error) {
            console.error('Error saving speaker:', error);
            return false;
        }
    };

    return {
        data,
        fetchSpeaker,
        saveSpeaker
    };
});
