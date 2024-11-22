export default class RequestQueue {
    constructor() {
        this.max = 3;
        this.queue = [];
        this.working = 0;

        // Replace mw.Api() with a general API request logic if needed
        // Otherwise, adjust according to your existing API logic
        this.api = {
            get: (params) => {
                return fetch(`/api/${params.action}`); // Example API request
            },
        };
    }

    push(callback) {
        return new Promise((resolve, reject) => {
            this.queue.push({
                resolve,
                reject,
                callback,
            });

            if (this.working < this.max) {
                this.working++;
                this.next();
            }
        });
    }

    force(callback) {
        return new Promise((resolve, reject) => {
            this.queue.unshift({
                resolve,
                reject,
                callback,
            });

            if (this.working < this.max) {
                this.working++;
                this.next();
            }
        });
    }

    next() {
        if (this.queue.length > 0) {
            const { callback, resolve, reject } = this.queue.shift();

            callback()
                .then(resolve)
                .catch(reject)
                .finally(() => {
                    this.working--;
                    this.next();
                });

        } else {
            this.working--;
        }
    }

    clearQueue() {
        this.queue.length = 0;
    }
}
