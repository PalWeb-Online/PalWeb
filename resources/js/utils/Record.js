import AudioRecord from './AudioRecord.js';

export default class Record {
    constructor(pronunciation) {
        this.file = null;
        this.stashkey = null;
        this.imageInfo = null;
        this.license = 'CC BY-SA';
        this.language = 'apc';
        this.speaker = null;
        this.pronunciation = pronunciation;
        this.audioRecord = null;
        this.extra = {};
        this.date = null;
        this.qualifier = null;
    }

    /**
     * Add extra metadata to the record.
     */
    setExtra(extra) {
        this.extra = extra;
    }

    getFilename() {
        const illegalChars = /[#<>[\]|{}:/\\]/g;

        let filename =
            this.language + '-' + this.speaker.dialect_id + '-' + this.pronunciation.translit + '-' + this.speaker.name + '.wav';

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

    /**
     * Clear the audio record file.
     */
    remove() {
        this.file = null;
    }

    /**
     * Reset the record object.
     */
    reset() {
        this.file = null;
        this.stashkey = null;
        this.imageInfo = null;
    }

    async uploadToStash() {
        if (!this.audioRecord) {
            return Promise.reject('[Record] cannot stash; no audio record initialized.');
        }

        const formData = new FormData();
        formData.append('file', this.audioRecord.getBlob(), this.getFilename());

        try {
            const response = await fetch('/api/record/stash', {
                method: 'POST',
                body: formData,
            });
            const result = await response.json();

            this.stashkey = result.stashkey;
            this.url = result.url;

        } catch (error) {
            return Promise.reject('[Record] stash upload failed: ' + error.message);
        }

        return Promise.resolve();
    }

    async finishUpload(apiEndpoint) {
        if (!this.stashkey) {
            return Promise.reject('[Record] cannot upload; no stashkey.');
        }

        try {
            const response = await fetch(apiEndpoint, {
                method: 'POST',
                body: JSON.stringify({
                    stashkey: this.stashkey,
                    metadata: {
                        speaker: this.speaker,
                        language: this.language,
                        pronunciation: this.pronunciation,
                        qualifier: this.qualifier,
                        license: this.license,
                        extra: this.extra
                    }
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const result = await response.json();
            this.imageInfo = result.imageInfo; // Adjust based on your API's response format
        } catch (error) {
            return Promise.reject('[Record] upload failed.');
        }

        return Promise.resolve();
    }
}
