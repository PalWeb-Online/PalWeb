/**
 * Some MIME-type analyzer are just checking if the UTF-8 decoded file
 * contains the strings "<?php" or "<\x00?\x00". So by banning 4 samples
 * ("?\x00" ; "\x00?" ; "ph" ; "hp"), we get rid of all problems
 */
const BANNED_SAMPLES = [0x003F, 0x3F00, 0x6870, 0x7068];

/**
 * @class AudioRecord
 * Represents an audio recording and provides a set of helper functions
 * to manipulate it in different contexts.
 */
export default class AudioRecord {

    /**
     * Creates a new AudioRecord instance.
     *
     * @param {Float32Array} [samples] The raw samples that will make up the record
     * @param {Number} [sampleRate] Rate at which the samples added to this object should be played
     */
    constructor(samples, sampleRate) {
        this.sampleRate = sampleRate;
        this.samples = samples;
    }

    /**
     * Change the sample rate.
     *
     * @param {Number} [value] New sample rate to set.
     */
    setSampleRate(value) {
        this.sampleRate = value;
    }

    /**
     * Get the sample rate in use.
     *
     * @return {Number} Current sample rate for the record.
     */
    getSampleRate() {
        return this.sampleRate;
    }

    /**
     * Get the total number of samples in the record.
     *
     * @return {Number} Number of samples.
     */
    getLength() {
        return this.samples.length;
    }

    /**
     * Get the duration of the record.
     *
     * This is based on the number of samples and the declared sample rate.
     *
     * @return {Number} Duration (in seconds) of the record.
     */
    getDuration() {
        return this.samples.length / this.sampleRate;
    }

    /**
     * Get all the raw samples that make up the record.
     *
     * @return {Float32Array} List of all samples.
     */
    getSamples() {
        return this.samples;
    }

    /**
     * Trim the record, starting from the beginning (left side).
     *
     * @param {Number} [duration] Duration (in seconds) to trim.
     */
    lTrim(duration) {
        const nbSamplesToRemove = Math.round(duration * this.sampleRate);

        if (nbSamplesToRemove >= this.samples.length) {
            this.clear();
            return;
        }

        this.samples = this.samples.subarray(nbSamplesToRemove);
    }

    /**
     * Trim the record, starting from the end (right side).
     *
     * @param {Number} [duration] Duration (in seconds) to trim.
     */
    rTrim(duration) {
        const nbSamplesToRemove = Math.round(duration * this.sampleRate);

        if (nbSamplesToRemove >= this.samples.length) {
            this.clear();
            return;
        }

        this.samples = this.samples.subarray(0, this.samples.length - nbSamplesToRemove);
    }

    /**
     * Clear the record.
     */
    clear() {
        this.samples = new Float32Array(0);
    }

    /**
     * Play the record to the audio output (aka the user's loudspeaker).
     */
    play() {
        const audioContext = new window.AudioContext();
        const buffer = audioContext.createBuffer(1, this.samples.length, 48000); // sample rate
        const channelData = buffer.getChannelData(0);

        for (let i = 0; i < this.samples.length; i++) {
            channelData[i] = this.samples[i];
        }

        const source = audioContext.createBufferSource();
        source.buffer = buffer;
        source.connect(audioContext.destination);
        source.start(0);
    }

    /**
     * Get a WAV-encoded Blob version of the record.
     *
     * @return {Blob} WAV-encoded audio record.
     * @alias getWAVE()
     */
    getBlob() {
        const buffer = new ArrayBuffer(44 + this.samples.length * 2);
        const view = new DataView(buffer);

        /* RIFF identifier */
        AudioRecord.writeString(view, 0, 'RIFF');
        /* file length */
        view.setUint32(4, 32 + this.samples.length * 2, true);
        /* RIFF type */
        AudioRecord.writeString(view, 8, 'WAVE');
        /* format chunk identifier */
        AudioRecord.writeString(view, 12, 'fmt ');
        /* format chunk length */
        view.setUint32(16, 16, true);
        /* sample format (raw) */
        view.setUint16(20, 1, true);
        /* channel count */
        view.setUint16(22, 1, true);
        /* sample rate */
        view.setUint32(24, this.sampleRate, true);
        /* byte rate (sample rate * block align) */
        view.setUint32(28, this.sampleRate * 2, true);
        /* block align (channel count * bytes per sample) */
        view.setUint16(32, 2, true);
        /* bits per sample */
        view.setUint16(34, 16, true);
        /* data chunk identifier */
        AudioRecord.writeString(view, 36, 'data');
        /* data chunk length */
        view.setUint32(40, this.samples.length * 2, true);

        for (let i = 0; i < this.samples.length; i++) {
            /* Convert 0->1 amplitude to 0->0x7FFF (signed 16-bit integer) */
            let sample = parseInt(this.samples[i] * 0x7FFF);
            /* Adjust banned samples */
            if (BANNED_SAMPLES.includes(sample)) {
                sample++;
            }
            /* Append the sample in the data chunk */
            view.setInt16(44 + i * 2, sample, true);
        }

        return new Blob([view], { "type": "audio/wav" });
    }

    /**
     * @alias getBlob()
     */
    getWAVE() {
        return this.getBlob();
    }

    /**
     * Generate an object URL representing the WAV-encoded record.
     *
     * You should unload the object URL once you're done with it.
     *
     * @return {DOMString} URL representing the record.
     */
    getObjectURL() {
        return window.URL.createObjectURL(this.getBlob());
    }

    /**
     * Start the download process of the record as a normal file.
     *
     * @param {String} [fileName='record.wav'] Name of the file to be downloaded.
     */
    download(fileName = 'record.wav') {
        const a = document.createElement('a');
        const url = this.getObjectURL();

        if (!fileName.toLowerCase().endsWith('.wav')) {
            fileName += '.wav';
        }

        a.style.display = "none";
        a.href = url;
        a.download = fileName;

        document.body.appendChild(a);
        a.click();

        // Delay URL revocation to ensure older browsers process the download
        setTimeout(() => {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 1000);
    }

    /**
     * Generate an HTML5 <audio> element containing the WAV-encoded record.
     *
     * @return {HTMLElement} Audio element containing the record.
     */
    getAudioElement() {
        const audio = document.createElement('audio');
        const source = document.createElement('source');

        source.src = this.getObjectURL();
        source.type = 'audio/wav';
        audio.appendChild(source);
        return audio;
    }

    /**
     * Internal static helper function used in getBlob to write a complete string at once
     * in a DataView object.
     *
     * @static
     * @param {DataView} dataview DataView in which to write.
     * @param {Number} offset Offset at which writing should start.
     * @param {String} str String to write in the DataView.
     * @private
     */
    static writeString(dataview, offset, str) {
        for (let i = 0; i < str.length; i++) {
            dataview.setUint8(offset + i, str.charCodeAt(i));
        }
    }
}
