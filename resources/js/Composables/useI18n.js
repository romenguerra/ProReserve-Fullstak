import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import es from '../messages/es.json';
import en from '../messages/en.json';

const messages = { es, en };
const fallbackLocale = ref(localStorage.getItem('locale') || 'es');

export function useI18n() {
    let page = null;
    try {
        page = usePage();
    } catch (e) {
        // Safe catch when called outside Vue component context (like in app.js setup)
    }
    
    const currentLocale = computed(() => {
        return (page && page.props && page.props.locale) || fallbackLocale.value;
    });

    // Sincronizar automáticamente con Ziggy y localStorage en cada cambio de idioma o navegación
    if (page) {
        watch(() => page.props?.locale, (newLocale) => {
            if (newLocale) {
                if (typeof window !== 'undefined' && window.Ziggy) {
                    window.Ziggy.defaults = window.Ziggy.defaults || {};
                    window.Ziggy.defaults.locale = newLocale;
                }
                localStorage.setItem('locale', newLocale);
            }
        }, { immediate: true });
    }

    const t = (key, defaultText = null) => {
        const keys = key.split('.');
        let value = messages[currentLocale.value];
        
        for (const k of keys) {
            value = value?.[k];
            if (value === undefined) break;
        }
        
        return value || defaultText || key;
    };

    const setLocale = (newLocale) => {
        if (messages[newLocale]) {
            fallbackLocale.value = newLocale;
            localStorage.setItem('locale', newLocale);
        }
    };

    // duplicate currentLocale removed
    return {
        t,
        setLocale,
        currentLocale,
        languages: Object.keys(messages)
    };
}
