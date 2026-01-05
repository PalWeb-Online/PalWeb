import { createI18n } from 'vue-i18n';
import en from '../../lang/en.json';
import es from '../../lang/es.json';
import ar from '../../lang/ar.json';

const messages = {
    en: en,
    es: es,
    ar: ar,
};

const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'en',
    messages,
});

export default i18n;
