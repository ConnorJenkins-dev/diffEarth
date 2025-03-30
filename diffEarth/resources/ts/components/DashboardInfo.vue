<script setup lang="ts">
import DashboardButton from "./DashboardButton.vue";
import { defineEmits, computed, ref, watch, nextTick } from "vue";
import EmailForm from "./EmailForm.vue";
import { useToast } from "../composables/useToast.ts";
import Weather from "./Weather.vue";
import { useTranslation } from "../composables/useTranslation"; // Importing translation

const { t } = useTranslation();
const { showToast } = useToast();

const emit = defineEmits([
    "enableEdit",
    "cancelEdit",
    "saveEdit",
    "publishLayout",
]);
const props = defineProps<{
    editMode: boolean;
    info: string;
    canSave: boolean;
    readOnly?: boolean;
}>();

const emailFormIsOpen = ref(false);
const emailFormContent = ref<HTMLElement | null>(null);
const innerContent = ref<HTMLElement | null>(null);
const deploymentInfo = ref(props.info);
const saveDisabled = ref(!props.canSave);

const userRole = ref(localStorage.getItem("role") || "user");

const isAdmin = computed(() => userRole.value === "admin");
const isCollaborator = computed(() => userRole.value === "collaborator");

const isLoggedIn = ref(!!localStorage.getItem("token"));

const emailAlertVisible = ref((isAdmin || isCollaborator) && isLoggedIn);

watch(
    () => props.canSave,
    (canSave) => {
        saveDisabled.value = !canSave;
    },
);

watch(
    () => props.info,
    (newInfo) => {
        deploymentInfo.value = newInfo;
    },
);

function toggleEmailForm() {
    emailFormIsOpen.value = !emailFormIsOpen.value;
}

// Animation to open email alerts
function enter(el: Element, done: () => void) {
    const element = el as HTMLElement;
    if (!innerContent.value) return done();

    element.style.height = "0px";
    innerContent.value.style.opacity = "0";

    requestAnimationFrame(() => {
        element.style.transition = "height 300ms ease";
        element.style.height = element.scrollHeight + "px";
    });

    setTimeout(() => {
        innerContent.value!.style.transition = "opacity 200ms ease";
        innerContent.value!.style.opacity = "1";
    }, 100);

    setTimeout(() => {
        element.style.height = "auto";
        done();
    }, 300);
}

// Animation to close email alerts
function leave(el: Element, done: () => void) {
    const element = el as HTMLElement;
    if (!innerContent.value) return done();

    innerContent.value.style.transition = "opacity 150ms ease";
    innerContent.value.style.opacity = "0";

    element.style.height = element.scrollHeight + "px";

    requestAnimationFrame(() => {
        element.style.transition = "height 300ms ease";
        element.style.height = "0px";
    });

    setTimeout(done, 300);
}

const warnSave = () => {
    showToast(
        "Cannot save: File still processing. Try again when it's finished.",
        "error",
        3000,
    );
};
</script>

<template>
    <h1 class="text-lg text-[var(--darkestBlue)] mb-2">{{ t.deployment }}:</h1>
    <div class="w-full h-2/3">
        <div v-if="editMode" class="h-full">
            <textarea
                class="w-full h-full backdrop-brightness-85 rounded-md p-2"
                v-model="deploymentInfo"
            ></textarea>
        </div>
        <h2 v-else class="text-md text-[var(--darkestBlue)]">
            {{ deploymentInfo }}
        </h2>
    </div>
    <Weather />
    <div class="border-t border-t-gray-800 my-4 w-3/4" />
    <!-- Email Form -->
    <div
        v-if="emailAlertVisible"
        class="max-w-md mx-auto bg-[var(--greyBlue)] rounded-2xl"
    >
        <div class="border rounded-2xl shadow p-3">
            <button
                class="w-full text-left font-semibold text-lg flex justify-between items-center cursor-pointer"
                @click="toggleEmailForm"
            >
                {{ t.setupEmailAlerts }}
                <span v-if="!emailFormIsOpen" class="pl-3 pi pi-chevron-down" />
                <span v-else class="pl-3 pi pi-chevron-up" />
            </button>

            <transition name="emailAccordion" @enter="enter" @leave="leave">
                <div
                    v-if="emailFormIsOpen"
                    ref="emailFormContent"
                    class="overflow-hidden text-gray-700 transition-all duration-300 ease-in-out p-2"
                >
                    <div ref="innerContent">
                        <EmailForm />
                    </div>
                </div>
            </transition>
        </div>
    </div>
    <div v-if="!readOnly" class="mt-auto flex flex-col items-center w-full">
        <div class="border-t border-t-gray-800 my-4 w-3/4" />
        <!-- Publish dashboard button -->
        <DashboardButton
            v-if="!editMode"
            class="my-2"
            @click="emit('publishLayout')"
        >
            {{ t.publishShareDashboard }}
        </DashboardButton>
        <!-- Save and edit dashboard buttons -->
        <DashboardButton v-if="!editMode" @click="emit('enableEdit')">
            {{ t.editDashboard }}
        </DashboardButton>
        <div v-else class="mt-auto flex flex-row items-center">
            <DashboardButton
                class="mx-3"
                @click="
                    saveDisabled ? warnSave() : emit('saveEdit', deploymentInfo)
                "
                >{{ t.save }}</DashboardButton
            >
            <DashboardButton class="mx-3" @click="emit('cancelEdit')">{{
                t.cancel
            }}</DashboardButton>
        </div>
    </div>
</template>

<style scoped></style>
