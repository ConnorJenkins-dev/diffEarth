import { computed, inject } from "vue";

export function useTranslation() {
    const currentLanguage = inject("currentLanguage");
    const translations = inject("translations");

    if (!currentLanguage || !translations) {
        throw new Error("Missing injected values in useTranslation");
    }

    return {
        t: computed(() => translations[currentLanguage.value]),
        currentLanguage,
    };
}
