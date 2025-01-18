import AudioRecord from './AudioRecord.js';

export default class Record {
    constructor(pronunciation) {
        this.id = null;
        this.url = '';
        this.audioRecord = null;
        this.stashKey = null;
        this.license = 'CC BY-SA';
        this.language = 'apc';
        this.speaker = null;
        this.pronunciation = pronunciation;
        this.qualifier = null;
        this.extra = {};
        this.date = null;
    }

    setExtra(extra) {
        this.extra = extra;
    }

    getFilename() {
        const illegalChars = /[#<>[\]|{}:/\\]/g;

        let filename =
            this.language + '-' +
            this.speaker.dialect_id + '-' +
            this.speaker.location_id + '-' +
            this.pronunciation.id + '-' +
            this.pronunciation.translit + '-' +
            this.speaker.id;

        return filename.replace(illegalChars, '-');
    }

    setSpeaker(speaker) {
        this.speaker = speaker;
    }

    setBlob(blob) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => {
                const arrayBuffer = reader.result;
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                audioContext.decodeAudioData(arrayBuffer)
                    .then((audioBuffer) => {
                        const samples = audioBuffer.getChannelData(0);
                        this.audioRecord = new AudioRecord(new Float32Array(samples), audioBuffer.sampleRate);
                        resolve();
                    })
                    .catch(reject);
            };
            reader.onerror = reject;
            reader.readAsArrayBuffer(blob);
        });
    }

    getBlob() {
        if (this.audioRecord) {
            return this.audioRecord.getBlob();
        } else {
            throw new Error('AudioRecord instance not initialized.');
        }
    }

    reset() {
        this.stashKey = null;
    }

    async stashRecord() {
        if (!this.audioRecord) throw new Error('AudioRecord instance not initialized. Cannot stash.');

        const formData = new FormData();
        formData.append('file', this.audioRecord.getBlob(), this.getFilename() + '.wav');
        formData.append('speakerId', this.speaker.id);

        try {
            const response = await axios.post('/api/record-wizard/store', formData);

            this.stashKey = response.data.stashKey;
            this.url = response.data.url;

        } catch (error) {
            throw new Error(`[Record] stash upload failed: ${error.response?.statusText || error.message}`);
        }
    }

    async uploadRecord() {
        if (!this.stashKey) {
            throw new Error('[Record] cannot upload; no stashKey.');
        }

        try {
            const response = await axios.post('/api/record-wizard/upload', {
                stashKey: this.stashKey,
                filename: this.getFilename(),
                speaker: this.speaker,
                pronunciation: this.pronunciation,
            });

            this.id = response.data.id;
            this.url = response.data.url;

        } catch (error) {
            throw new Error(`[Record] Upload failed: ${error.response?.data?.message || error.message}`);
        }
    }
}
