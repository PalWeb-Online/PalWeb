import AudioRecord from './AudioRecord.js';

export default class Record {
    constructor(pronunciation) {
        this.id = null;
        this.url = '';
        this.audioRecord = null;
        this.stashkey = null;
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
            this.pronunciation.translit + '-' +
            this.speaker.id + '.wav';

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
        this.stashkey = null;
    }

    async stashRecord() {
        if (!this.audioRecord) throw new Error('AudioRecord instance not initialized. Cannot stash.');

        const formData = new FormData();
        formData.append('file', this.audioRecord.getBlob(), this.getFilename());
        formData.append('speakerId', this.speaker.id);

        try {
            const response = await fetch('/api/record/stash', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) throw new Error(`[Record] stash upload failed: ${response.statusText}`);

            const result = await response.json();
            this.stashkey = result.stashkey;
            this.url = result.url;

        } catch (error) {
            throw new Error(`[Record] stash upload failed: ${error.message}`);
        }
    }

    async uploadRecord() {
        if (!this.stashkey) {
            throw new Error('[Record] cannot upload; no stashkey.');
        }

        try {
            const response = await fetch('/api/record/upload', {
                method: 'POST',
                body: JSON.stringify({
                    stashkey: this.stashkey,
                    language: this.language,
                    speaker: this.speaker,
                    pronunciation: this.pronunciation,
                }),
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) throw new Error(`[Record] Upload failed with status ${response.status}`);

            const result = await response.json();
            this.id = result.id;
            this.url = result.url;
            this.stashkey = null;

            if (!result.success) throw new Error(result.message || '[Record] Upload failed.');

        } catch (error) {
            console.error(error);
            throw new Error(`[Record] Upload failed: ${error.message}`);
        }
    }
}
