import { reactive } from 'vue';

export function useConfigStore() {
    const data = reactive({
        ...window.RecordWizardConfig,
        pastRecords: {}
    });

    function fetchPastRecords(langQid, speakerQid, deferred = null, offset = 0) {
        const promise = deferred || new Promise((resolve, reject) => {
            deferred = { resolve, reject };
        });

        if (data.pastRecords[langQid] !== undefined && offset === 0) {
            deferred.resolve(data.pastRecords[langQid]);
        } else {
            fetch(`/api/records?speaker=${speakerQid}&language=${langQid}&limit=max&offset=${offset}`) // Modify the endpoint accordingly
                .then((res) => res.json())
                .then((result) => {
                    if (data.pastRecords[langQid] === undefined) {
                        data.pastRecords[langQid] = [];
                    }
                    data.pastRecords[langQid].push(...result.records);

                    if (result.continue !== undefined) {
                        fetchPastRecords(langQid, speakerQid, deferred, result.continue.offset);
                    } else {
                        deferred.resolve(data.pastRecords[langQid]);
                    }
                })
                .catch(() => {
                    data.pastRecords[langQid] = [];
                    deferred.reject();
                });
        }

        return promise;
    }

    function pushPastRecords(langQid, speakerQid, pastRecords) {
        if (data.pastRecords[langQid] === undefined) {
            data.pastRecords[langQid] = [];
        }
        data.pastRecords[langQid].push(...pastRecords);
    }

    return {
        data,
        fetchPastRecords,
        pushPastRecords
    };
}
