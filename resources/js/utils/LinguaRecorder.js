import recordingProcessorEncapsulation from './RecordingProcessor';
import AudioRecord from './AudioRecord';

const STATE = {
    stop: 'stop',
    listening: 'listen',
    recording: 'record',
    paused: 'pause',
};

/**
 * @class LinguaRecorder
 * Provides many powerful tools to easily perform audio recordings.
 */
export default class LinguaRecorder {
    stream = null;
    recordProcessorConfig = {};
    audioContext = null;
    audioInput = null;
    processor = null;
    _state = STATE.stop;
    _isConnected = false;
    _duration = 0;
    _extraAudioNodes = [];
    _eventHandlers = {
        ready: [],
        readyFail: [],
        started: [],
        listening: [],
        recording: [],
        saturated: [],
        paused: [],
        stopped: [],
        canceled: [],
    };
    _eventStorage = {
        ready: null,
        readyFail: null,
    };

    /**
     * Creates a new LinguaRecorder instance
     *
     * @param {Object} [config] Configuration options to pass to the RecordingProcessor
     */
    constructor(config) {
        this.setConfig(config);
        this._getAudioStream();
    }

    /**
     * Change the processor configuration.
     *
     * @param {Object} [config] Configuration options, see the constructor for config documentation.
     * @chainable
     */
    setConfig(config) {
        this.recordProcessorConfig = {
            ...this.recordProcessorConfig,
            ...config,
        };

        this._sendCommandToProcessor('setConfig', this.recordProcessorConfig);

        return this;
    }

    /**
     * Return the current duration of the recording.
     *
     * @return {Number} The duration in seconds
     */
    getRecordingTime() {
        return this._duration;
    }

    /**
     * Return the current state of the recorder.
     *
     * @return {String} One of the following: 'stop', 'listening', 'recording', 'paused'
     */
    getState() {
        return this._state;
    }

    /**
     * Return the audioContext initialized and used by the recorder.
     * see https://developer.mozilla.org/fr/docs/Web/API/AudioContext
     *
     * @return {AudioContext} The AudioContext object used by the recorder.
     */
    getAudioContext() {
        return this.audioContext;
    }

    /**
     * Start to record.
     * If autoStart is set to true, enter in listening state and postpone the start
     * of the recording when a voice will be detected.
     * @chainable
     */
    start() {
        if (this.processor === null) {
            return this;
        }

        this._connect();
        return this._sendCommandToProcessor('start');
    }

    /**
     * Switch the record to the pause state.
     * While in pause, all the inputs coming from the microphone will be ignored.
     * To resume to the recording state, just call the start() method again.
     * @chainable
     */
    pause() {
        return this._sendCommandToProcessor('pause');
    }

    /**
     * Stop the recording process and fire the record to the user.
     * Depending on the configuration, this method could discard the record
     * if it fails some quality controls (duration and saturation).
     * @param {Boolean} [cancelRecord] If set to true, cancel and discard the record in any case.
     * @chainable
     */
    stop(cancelRecord) {
        if (cancelRecord === true) {
            this.cancel();
        } else {
            return this._sendCommandToProcessor('stop');
        }
    }

    /**
     * Stop a recording, but without saving the record.
     * @chainable
     */
    cancel() {
        return this._sendCommandToProcessor('cancel');
    }

    /**
     * Toggle between the recording and stopped state.
     * @chainable
     */
    toggle() {
        return this._sendCommandToProcessor('toggle');
    }

    /**
     * Attach a handler function to a given event.
     * @param {String} [event] Name of an event.
     * @param {Function} [handler] A function to execute when the event is triggered.
     * @chainable
     */
    on(event, handler) {
        if (event === 'stoped') {
            event = 'stopped';
            console.warn('[LinguaRecorder] .on("stoped",...) is deprecated. Please use .on("stopped",...) instead.');
        }

        if (event in this._eventHandlers) {
            this._eventHandlers[event].push(handler);
        }

        if (event in this._eventStorage && this._eventStorage[event] !== null) {
            handler(this._eventStorage[event]);
        }

        return this;
    }

    /**
     * Remove all the handler function from an event.
     * @param {String} [event] Name of an event.
     * @chainable
     */
    off(event) {
        if (event === 'stoped') {
            event = 'stopped';
            console.warn('[LinguaRecorder] .off("stoped") is deprecated. Please use .off("stopped") instead.');
        }

        if (event in this._eventHandlers) {
            this._eventHandlers[event] = [];
        }

        return this;
    }

    /**
     * Add an extra AudioNode
     * @param {AudioNode} [node] Node to connect inside the recording context.
     * @chainable
     */
    connectAudioNode(node) {
        const wasConnected = this._isConnected;
        if (this._isConnected) {
            this._disconnect();
        }
        this._extraAudioNodes.push(node);
        if (wasConnected) {
            this._connect();
        }
        return this;
    }

    /**
     * Remove an extra AudioNode
     * @param {AudioNode} [node] Node to disconnect from the recording context.
     * @chainable
     */
    disconnectAudioNode(node) {
        for (let i = 0; i < this._extraAudioNodes.length; i++) {
            if (node === this._extraAudioNodes[i]) {
                const wasConnected = this._isConnected;
                if (this._isConnected) {
                    this._disconnect();
                }
                this._extraAudioNodes.splice(i, 1);
                if (wasConnected) {
                    this._connect();
                }
                break;
            }
        }
        return this;
    }

    /**
     * Cleanly stop the threaded execution of the audio recorder in preparation
     * for the destruction of the current LinguaRecorder instance.
     * @chainable
     */
    close() {
        if (this.processor === null) {
            return;
        }

        this.off('ready').off('readyFail').off('started').off('listening')
            .off('recording').off('saturated').off('paused')
            .off('stopped').off('canceled');
        this._eventStorage = {};

        this._sendCommandToProcessor('close');
        this._disconnect();
        this.processor.port.onmessage = null;
        this.processor.port.close();
        this.processor = null;

        return this;
    }

    /**
     * Send a message to the Recording Processor to change its behavior.
     * @private
     */
    _sendCommandToProcessor(command, extra) {
        if (this.processor !== null) {
            this.processor.port.postMessage({ message: command, extra });
        }
        return this;
    }

    /**
     * Fire a given event to all the registered handler functions.
     * @private
     */
    _fire(event, value) {
        if (event in this._eventHandlers) {
            for (const handler of this._eventHandlers[event]) {
                handler(value);
            }
        }

        if (event in this._eventStorage) {
            this._eventStorage[event] = value;
        }
    }

    /**
     * Try to get a MediaStream object with tracks containing an audio input from the microphone.
     * @private
     */
    async _getAudioStream() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
        } catch (error) {
            this._fire('readyFail', error);
            return;
        }

        await this._initStream();
        this._fire('ready', this.stream);
    }

    /**
     * Called once we got a MediaStream. Create an AudioContext and needed AudioNodes.
     * @private
     */
    async _initStream() {
        this.audioContext = new window.AudioContext();
        this.audioInput = this.audioContext.createMediaStreamSource(this.stream);

        const blob = new Blob([`(${recordingProcessorEncapsulation})()`], { type: 'application/javascript; charset=utf-8' });
        await this.audioContext.audioWorklet.addModule(URL.createObjectURL(blob));

        this.recordProcessorConfig.sampleRate = this.audioContext.sampleRate;
        this.processor = new AudioWorkletNode(this.audioContext, 'recording-processor', { processorOptions: this.recordProcessorConfig });

        this.processor.port.onmessage = (event) => {
            switch (event.data.message) {
                case 'started':
                    this._state = STATE.recording;
                    this._duration = 0;
                    this._fire('started');
                    break;
                case 'listening':
                    this._state = STATE.listening;
                    this._duration = 0;
                    this._fire('listening', event.data.samples);
                    break;
                case 'recording':
                    this._state = STATE.recording;
                    this._duration = event.data.duration;
                    this._fire('recording', event.data.samples);
                    break;
                case 'saturated':
                    this._fire('saturated');
                    break;
                case 'paused':
                    this._state = STATE.paused;
                    this._fire('paused');
                    break;
                case 'stopped':
                    this._state = STATE.stop;
                    this._duration = 0;
                    this._disconnect();
                    this._fire('stopped', new AudioRecord(event.data.record, this.audioContext.sampleRate));
                    break;
                case 'canceled':
                    this._state = STATE.stop;
                    this._duration = 0;
                    this._disconnect();
                    this._fire('canceled', event.data.reason);
                    break;
            }
        };
    }

    /**
     * Connect the audioInput node to the processor node.
     * @private
     */
    _connect() {
        if (this._isConnected) {
            return;
        }

        let currentNode = this.audioInput;
        for (const node of this._extraAudioNodes) {
            currentNode.connect(node);
            currentNode = node;
        }
        currentNode.connect(this.processor);

        this._isConnected = true;
    }

    /**
     * Disconnect the audioInput node from the currently connected processor node.
     * @private
     */
    _disconnect() {
        if (!this._isConnected) {
            return;
        }

        this.audioInput.disconnect();
        for (const node of this._extraAudioNodes) {
            node.disconnect();
        }
        this.processor.disconnect();

        this._isConnected = false;
    }
}
