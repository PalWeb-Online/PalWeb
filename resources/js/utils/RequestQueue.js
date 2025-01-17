export default class RequestQueue {
    constructor() {
        this.max = 3;
        this.queue = [];
        this.working = 0;
        this.delay = 500;

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

            setTimeout(async () => {
                try {
                    const result = await callback();
                    resolve(result);

                } catch (error) {
                    reject(error);

                } finally {
                    this.working--;
                    if (this.queue.length > 0) {
                        this.next();
                    }
                }
            }, this.delay);

        } else {
            this.working--;
        }
    }

    clearQueue() {
        this.queue.length = 0;
    }
}
