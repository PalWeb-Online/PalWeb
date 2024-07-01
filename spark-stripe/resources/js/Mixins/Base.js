export default {
    methods: {
        __(key, replace) {
            var translation = window.translations[key] ? window.translations[key] : key;

            _.forEach(replace, (value, key) => {
                translation = translation.replace(':'+key, value);
            });

            return translation;
        },
    },
}
