<script setup>
import { ref } from "vue";
import { useToast } from "../composables/useToast";
import { useTranslation } from "../composables/useTranslation";

const { t } = useTranslation();

const showModal = ref(false);
const selectedFile = ref(null);
const { showToast } = useToast();
const uploading = ref(false);

function openModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}

function onFileChange(event) {
    const target = event.target;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        // Check if file size is greater than 20MB
        if (file.size > 20 * 1024 * 1024) {
            showToast(t.fileSizeError, "error");
            selectedFile.value = null;
            return;
        }
        selectedFile.value = file;
    }
}

function showAToast() {
    showToast("Hello");
}

async function handleUpload() {
    if (!selectedFile.value) {
        showToast(t.selectFileError, "error");
        return;
    }
    uploading.value = true;
    showToast(t.uploading, "success", 3000);

    const formData = new FormData();
    formData.append("file", selectedFile.value);

    try {
        const response = await fetch("/api/upload", {
            method: "POST",
            body: formData,
            headers: {
                Accept: "application/json",
            },
        });
        if (response) {
            uploading.value = false;
        }
        const data = await response.json();

        if (response.status === 422) {
            showToast(data.message, "error");
            return;
        }

        if (!response.ok) {
            showToast(data.message, "error");
            return;
        }

        showToast(data.message, "success");
        closeModal();
    } catch (error) {
        showToast(error, "error");
    }
}
</script>

<template>
    <div>
        <button
            @click="openModal"
            class="bg-[var(--lightBlue)] border-2 border-[var(--darkestBlue)] rounded-lg px-4 py-2 font-bold transition hover:bg-[var(--darkestBlue)] hover:border-[var(--lightBlue)] hover:text-white"
        >
            {{ t.uploadCsv }}
        </button>
        <transition name="modal">
            <div
                v-if="showModal"
                class="fixed inset-0 bg-gray-800/50 flex items-center justify-center"
                @click.self="closeModal"
            >
                <div class="bg-white rounded shadow-lg w-1/3 p-6 relative">
                    <button
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                        @click="closeModal"
                    >
                        &#10006;
                    </button>
                    <h2 class="text-xl font-bold mb-4 text-black">
                        {{ t.uploadFile }}
                    </h2>

                    <form @submit.prevent="handleUpload">
                        <label
                            class="block mb-2 text-sm font-medium text-gray-700"
                        >
                            {{ t.chooseFile }}

                            <input
                                type="file"
                                class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded"
                                @change="onFileChange"
                            />
                        </label>

                        <div class="flex justify-end space-x-2">
                            <button
                                type="submit"
                                :disabled="uploading"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <template v-if="uploading">
                                    <svg
                                        class="animate-spin h-5 w-5 inline-block mr-2"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                        ></path>
                                    </svg>
                                </template>
                                <template v-else> {{ t.upload }} </template>
                            </button>
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500"
                            >
                                {{ t.cancel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
/* Simple Fade-In/Out transition */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}
.modal-enter,
.modal-leave-to {
    opacity: 0;
}
</style>
