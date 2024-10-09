// This function is used to encapsulate the code, so that it can be
// stringified, put in an objectURL, and loaded as a Worklet module
// from within LinguaRecorder without needing to know the path of this file.

export default function recordingProcessorEncapsulation() {
    const STATE = {
        stop: 'stop',
        listening: 'listen',
        recording: 'record',
        paused: 'pause',
    };

    /**
     * @class AudioSamples
     * Internal class used to store and manage a set of audio samples
     * during the creation of an audio recording.
     *
     * @private
     */
    class AudioSamples {

        /**
         * Creates a new AudioSamples instance.
         *
         * @param {Number} [sampleRate] Rate at which the samples added to this object should be played
         */
        constructor(sampleRate) {
            this.sampleRate = sampleRate;
            this.sampleBlocs = [];
            this.length = 0;
        }

        /**
         * Add some raw samples to the record.
         *
         * @param {Float32Array} [samples] samples to append to the record
         * @param {Number} [rollingDuration=0] (optional) if set, last number of seconds of the record to keep after adding the new samples
         * @return {Number} the new total number of samples stored.
         */
        push(samples, rollingDuration) {
            this.length += samples.length;
            this.sampleBlocs.push(samples);

            if (rollingDuration !== undefined) {
                let duration = this.getDuration();
                if (duration > rollingDuration) {
                    this.lTrim(duration - rollingDuration);
                }
            }

            return this.length;
        }

        /**
         * Get all the raw samples that make up the record.
         *
         * @return {Float32Array} list of all samples.
         */
        get() {
            const flattened = new Float32Array(this.length);
            let offset = 0;

            for (const bloc of this.sampleBlocs) {
                flattened.set(bloc, offset);
                offset += bloc.length;
            }

            return flattened;
        }

        /**
         * Get the duration of the record.
         * This is based on the number of samples and the declared sample rate.
         *
         * @return {Number} duration (in seconds) of the record.
         */
        getDuration() {
            return this.length / this.sampleRate;
        }

        /**
         * Trim the record, starting with the beginning of the record (the left side).
         *
         * @param {Number} [duration] duration (in seconds) to trim
         */
        lTrim(duration) {
            let nbSamplesToRemove = Math.round(duration * this.sampleRate);

            if (nbSamplesToRemove >= this.length) {
                this.sampleBlocs = [];
                return;
            }

            this.length -= nbSamplesToRemove;
            while (nbSamplesToRemove > 0 && nbSamplesToRemove >= this.sampleBlocs[0].length) {
                nbSamplesToRemove -= this.sampleBlocs[0].length;
                this.sampleBlocs.shift();
            }
            if (nbSamplesToRemove > 0) {
                this.sampleBlocs[0] = this.sampleBlocs[0].subarray(nbSamplesToRemove);
            }
        }

        /**
         * Trim the record, starting with the end of the record (the right side).
         *
         * @param {Number} [duration] duration (in seconds) to trim
         */
        rTrim(duration) {
            let nbSamplesToRemove = Math.round(duration * this.sampleRate);

            if (nbSamplesToRemove >= this.length) {
                this.sampleBlocs = [];
                return;
            }

            this.length -= nbSamplesToRemove;
            while (nbSamplesToRemove > 0 && nbSamplesToRemove >= this.sampleBlocs[this.sampleBlocs.length - 1].length) {
                nbSamplesToRemove -= this.sampleBlocs[this.sampleBlocs.length - 1].length;
                this.sampleBlocs.pop();
            }
            if (nbSamplesToRemove > 0) {
                const lastIndex = this.sampleBlocs.length - 1;
                this.sampleBlocs[lastIndex] = this.sampleBlocs[lastIndex].subarray(0, this.sampleBlocs[lastIndex].length - nbSamplesToRemove);
            }
        }
    }


    /**
     * @class RecordingProcessor
     * @extends AudioWorkletProcessor
     * Internal class used to do the audio recording process
     * on the Web Audio rendering thread.
     *
     * @private
     */
    class RecordingProcessor extends AudioWorkletProcessor {

        /**
         * Creates a new RecordingProcessor instance.
         * It cannot be instantiated directly, it will be called internally by the creation of an associated AudioWorkletNode.
         *
         * @param {Object} [options] Configuration options
         */
        constructor(options) {
            super();

            this.config = {
                autoStart: false,
                autoStop: false,
                timeLimit: 0,
                onSaturate: 'none',
                saturationThreshold: 0.99,
                startThreshold: 0.1,
                stopThreshold: 0.05,
                stopDuration: 0.3,
                marginBefore: 0.25,
                marginAfter: 0.25,
                minDuration: 0.15,
            };
            this._isRunning = true;
            this._state = STATE.stop;
            this._audioSamples = null;
            this._silenceSamplesCount = 0;
            this._isSaturated = false;

            this._setConfig(options.processorOptions);
            this.port.onmessage = (event) => {
                switch (event.data.message) {
                    case 'start':
                        this._start();
                        break;

                    case 'pause':
                        this._pause();
                        break;

                    case 'stop':
                        this._stop();
                        break;

                    case 'cancel':
                        this._stop(true);
                        break;

                    case 'toggle':
                        if (this._state === STATE.recording || this._state === STATE.listening) {
                            this._stop();
                        } else {
                            this._start();
                        }
                        break;

                    case 'close':
                        this._isRunning = false;
                        break;

                    case 'setConfig':
                        this._setConfig(event.data.extra);
                        break;
                }
            };
        }

        /**
         * Set configuration options
         *
         * @param {Object} [options] Configuration options
         */
        _setConfig(options) {
            this.config = {
                ...this.config,
                ...options,
            };
        }

        /**
         * Preparation and switch state to start recording.
         */
        _start() {
            if (this._state === STATE.listening || this._state === STATE.recording) {
                return;
            }

            if (this._state === STATE.stop) {
                this._audioSamples = new AudioSamples(this.config.sampleRate);
                this._silenceSamplesCount = 0;
                this._isSaturated = false;

                if (this.config.autoStart) {
                    this._state = STATE.listening;
                    return;
                }
            }

            this._state = STATE.recording;
            this.port.postMessage({ message: 'started' });
        }

        /**
         * Switch the record to the pause state.
         */
        _pause() {
            if (this._state === STATE.stop || this._state === STATE.paused) {
                return;
            }

            if (this._state === STATE.listening) {
                this._state = STATE.stop;
            } else {
                this._state = STATE.paused;
            }

            this.port.postMessage({ message: 'paused' });
        }

        /**
         * Stop the recording process and send the record to the AudioWorkletNode.
         *
         * Depending on the configuration, this method could discard the record
         * if it fails some quality controls (duration and saturation).
         *
         * @param {Boolean} [cancelRecord] If set to true, cancel and discard the record in any cases.
         */
        _stop(cancelRecord = false) {
            if (this._state === STATE.stop) {
                return;
            }

            if (cancelRecord) {
                this._audioSamples = null;
                this.port.postMessage({ message: 'canceled', reason: 'asked' });
            } else if ((this.config.onSaturate === 'discard' || this.config.onSaturate === 'cancel') && this._isSaturated) {
                this._audioSamples = null;
                this.port.postMessage({ message: 'canceled', reason: 'saturated' });
            } else if (this._audioSamples.getDuration() < this.config.minDuration) {
                this._audioSamples = null;
                this.port.postMessage({ message: 'canceled', reason: 'tooShort' });
            } else {
                this.port.postMessage({ message: 'stopped', record: this._audioSamples.get() });
            }

            this._state = STATE.stop;
        }

        /**
         * Process and save audio inputs depending on the current state of the recorder.
         *
         * @param {Array} [inputs] Array of inputs connected to the node, each containing an array of channels, each containing a Float32Array of samples.
         * @param {Array} [outputs] Array of outputs, which structure is similar to inputs
         * @param {Object} [parameters] unused
         * @return {Boolean} Whether or not to force the AudioWorkletNode to remain active
         */
        process(inputs, outputs) {
            if (inputs.length === 0 || inputs[0].length === 0) {
                return this._isRunning;
            }

            if (this._state === STATE.listening) {
                this._audioListeningProcess(new Float32Array(inputs[0][0]));
            } else if (this._state === STATE.recording) {
                this._audioRecordingProcess(new Float32Array(inputs[0][0]));
            }

            // Pass data directly to output, unchanged (if needed)
            for (let sample = 0; sample < inputs[0][0].length; sample++) {
                outputs[0][0][sample] = inputs[0][0][sample];
            }

            return this._isRunning;
        }

        /**
         * Handle auto-start recording or store the last marginBefore seconds incoming from the microphone.
         *
         * @param {Float32Array} [samples] Array of audio samples to process.
         * @private
         */
        _audioListeningProcess(samples) {
            for (const amplitude of samples) {
                if (Math.abs(amplitude) > this.config.startThreshold) {
                    this._state = STATE.recording;
                    this.port.postMessage({ message: 'started' });
                    return this._audioRecordingProcess(samples);
                }
            }

            if (this.config.marginBefore > 0) {
                this._audioSamples.push(samples, this.config.marginBefore);
            }
            this.port.postMessage({ message: 'listening', samples });
        }

        /**
         * Saves the incoming audio stream from the user's microphone into the AudioSamples storage.
         *
         * @param {Float32Array} [samples] Array of audio samples to process.
         * @private
         */
        _audioRecordingProcess(samples) {
            this._audioSamples.push(samples);
            this.port.postMessage({
                message: 'recording',
                samples,
                duration: this._audioSamples.getDuration(),
            });

            for (const amplitude of samples) {
                if (Math.abs(amplitude) > this.config.saturationThreshold) {
                    this.port.postMessage({ message: 'saturated' });
                    this._isSaturated = true;
                    if (this.config.onSaturate === 'cancel') {
                        this._stop();
                        return;
                    }
                    break;
                }
            }

            if (this.config.autoStop) {
                const amplitudeMax = Math.max(...samples.map(Math.abs));
                if (amplitudeMax < this.config.stopThreshold) {
                    this._silenceSamplesCount += samples.length;

                    if (this._silenceSamplesCount >= (this.config.stopDuration * this.config.sampleRate)) {
                        this._audioSamples.rTrim(this.config.stopDuration - this.config.marginAfter);
                        this._stop();
                    }
                } else {
                    this._silenceSamplesCount = 0;
                }
            }

            if (this.config.timeLimit > 0 && this.config.timeLimit >= this._audioSamples.getDuration()) {
                this._stop();
            }
        }
    }

    // Register our AudioWorkletProcessor so it can be used by an AudioWorkletNode
    registerProcessor('recording-processor', RecordingProcessor);
}
