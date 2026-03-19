import { ref, computed } from 'vue';
import es from '../messages/es.json';
import en from '../messages/en.json';

const messages = { es, en };
const locale = ref(localStorage.getItem('locale') || 'es');

export function useI18n() {
    const t = (key) => {
        const keys = key.split('.');
        let value = messages[locale.value];
        
        for (const k of keys) {
            value = value?.[k];
            if (value === undefined) break;
        }
        
        return value || key;
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
