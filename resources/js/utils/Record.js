export default class Record {
    constructor(pronunciation) {
        this.file = null;
        this.stashkey = null;
        this.imageInfo = null;
        this.speaker = null;
        this.language = 'Palestinian Arabic';
        this.license = 'CC BY-SA';
        this.transcription = pronunciation;
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

    /**
     * Generate a filename for the record based on current metadata.
     */
    getFilename() {
        const illegalChars = /[#<>[\]|{}:/\\]/g;
        let filename = 'LL' +
            '-' + this.language.iso3 +
            '-' + this.speaker.name +
            '-' + this.transcription + '.wav';

        return filename.replace(illegalChars, '-');
    }

    /**
     * Set the speaker object for the record.
     */
    setSpeaker(speaker) {
        this.speaker = speaker;
        return this;
    }

    /**
     * Set the language object for the record.
     */
    setLanguage(language) {
        this.language = language;
        return this;
    }

    /**
     * Set the Blob file (audio) for this record.
     */
    setBlob(audioBlob, extension) {
        this.reset();
        this.file = audioBlob;
        this.date = new Date();
        return true;
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

    /**
     * Upload the audio file to a server.
     * Replace the `uploadToStash` logic with your backend server's API endpoint.
     */
    async uploadToStash(apiEndpoint) {
        if (!this.file) {
            return Promise.reject('[Record] cannot stash; no file set.');
        }

        const formData = new FormData();
        formData.append('file', this.file, this.getFilename());

        try {
            const response = await fetch(apiEndpoint, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            this.stashkey = result.stashkey; // Assuming 'stashkey' is returned from the server
            this.fileUrl = result.url; // URL from DigitalOcean Spaces
        } catch (error) {
            return Promise.reject('[Record] stash upload failed.');
        }

        return Promise.resolve();
    }

    /**
     * Finalize the upload by publishing the audio to the server.
     * Replace `finishUpload` logic with your backend server's API endpoint.
     */
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
                        transcription: this.transcription,
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
