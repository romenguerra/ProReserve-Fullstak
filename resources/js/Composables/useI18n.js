import { ref, computed } from 'vue';
import es from '../locales/es.json';
import en from '../locales/en.json';

const messages = { es, en };
const locale = ref(localStorage.getItem('locale') || 'es');

export function useI18n() {
    const t = (key) => {
        return messages[locale.value][key] || key;
    };

    const setLocale = (newLocale) => {
        if (messages[newLocale]) {
            locale.value = newLocale;
            localStorage.setItem('locale', newLocale);
        }
    };

    const currentLocale = computed(() => locale.value);

    return {
        t,
        setLocale,
        currentLocale,
        languages: Object.keys(messages)
    };
}
