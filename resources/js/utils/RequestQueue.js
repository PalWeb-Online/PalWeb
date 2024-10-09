export default class RequestQueue {
    constructor() {
        this.maxConcurrentRequests = 3; // Limit to 3 simultaneous requests
        this.queue = []; // Queue to store pending requests
        this.currentRequests = 0; // Tracks how many requests are currently running

        // Replace mw.Api() with a general API request logic if needed
        // Otherwise, adjust according to your existing API logic
        this.api = {
            get: (params) => {
                return fetch(`/api/${params.action}`); // Example API request
            },
        };
    }

    /**
     * Adds a request to the queue. Starts processing if there are available slots.
     * @param {Function} callback - A function that returns a Promise.
     * @returns {Promise} - A promise that resolves or rejects when the request is processed.
     */
    push(callback) {
        return new Promise((resolve, reject) => {
            this.queue.push({
                resolve,
                reject,
                callback,
            });

            if (this.currentRequests < this.maxConcurrentRequests) {
                this.currentRequests++;
                this.next();
            }
        });
    }

    /**
     * Adds a request to the front of the queue (high priority).
     * @param {Function} callback - A function that returns a Promise.
     * @returns {Promise} - A promise that resolves or rejects when the request is processed.
     */
    force(callback) {
        return new Promise((resolve, reject) => {
            this.queue.unshift({
                resolve,
                reject,
                callback,
            });

            if (this.currentRequests < this.maxConcurrentRequests) {
                this.currentRequests++;
                this.next();
            }
        });
    }

    /**
     * Processes the next request in the queue.
     * @private
     */
    next() {
        if (this.queue.length > 0) {
            const { callback, resolve, reject } = this.queue.shift();

            // Run the callback and handle the returned Promise
            callback()
                .then(resolve)
                .catch(reject)
                .finally(() => {
                    this.currentRequests--;
                    this.next(); // Process the next request in the queue
                });
        } else {
            this.currentRequests--;
        }
    }

    /**
     * Clears all pending requests from the queue.
     */
    clearQueue() {
        this.queue.length = 0; // Empty the queue
    }
}
