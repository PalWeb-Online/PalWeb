import {defineStore} from 'pinia';
import {reactive} from 'vue';
import axios from 'axios';

export const useSpeakerStore = defineStore('SpeakerStore', () => {
    const speaker = reactive({
        id: null,
        user: {
            id: null,
            name: '',
            avatar: '',
        },
        dialect: {
            id: null,
            name: '',
        },
        location: {
            id: '',
            name_ar: '',
            name_en: '',
        },
        fluency: '',
        gender: '',
    });

    const saveSpeaker = async () => {
        try {
            const response = await axios.post('/workbench/record-wizard/speaker', {
                user_id: speaker.user.id,
                dialect_id: speaker.dialect.id,
                location_id: speaker.location.id,
                fluency: speaker.fluency,
                gender: speaker.gender,
            });

            Object.assign(speaker, response.data.speaker, {
                name: response.data.name,
                avatar: response.data.avatar,
            });
            alert(response.data.message);

            return true;

        } catch (error) {
            console.error('Error saving speaker:', error);
            return false;
        }
    };

    return {
        speaker,
        saveSpeaker
    };
});
