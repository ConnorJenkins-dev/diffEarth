<script setup lang="ts">
import { useToast } from "../composables/useToast";
// Variables
import { onMounted, ref } from "vue";

const image = ref<File | null>(null);
const postTitle = ref("");
const postContent = ref("");
const { showToast } = useToast();
const fileInputRef = ref<HTMLInputElement | null>(null);

const userId = ref(null);

// Functions

//trigger file input
function triggerFileInput() {
    fileInputRef.value?.click();
}

function cleanConstants() {
    postTitle.value = "";
    postContent.value = "";
    image.value = null;
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        const validTypes = ["image/jpeg", "image/jpg"];

        if (!validTypes.includes(file.type)) {
            showToast("Only .jpg or .jpeg files are allowed", "error", 3000);
            return;
        }

        image.value = file;
    }
}

// fetchUser Pair Programming with Cole
const fetchUser = async () => {
    try {
        const token = localStorage.getItem("token");
        if (!token) {
            throw new Error("No token found");
        }

        const response = await fetch(
            `${import.meta.env.BASE_URL}api/user-get`,
            {
                method: "GET",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            },
        );

        if (!response.ok) {
            throw new Error("Error fetching user");
        }

        const userToken = await response.json();
        userId.value = userToken.user.id;
    } catch (error) {
        console.error(error);
        return;
    }
};
async function postForm() {
    // Get current user token
    await fetchUser();

    if (!userId.value) {
        showToast("Please login to add a post", "error", 3000);
        return;
    }

    const formData = new FormData();
    formData.append("postContent", postContent.value);
    formData.append("postTitle", postTitle.value);
    if (image.value) {
        formData.append("img", image.value);
    }
    formData.append("userId", userId.value);

    if (postContent.value == "" && image.value == null) {
        showToast("Please add a post or an image", "error", 3000);
        return;
    }
    try {
        // Post to API
        const response = await fetch(`${import.meta.env.BASE_URL}api/post`, {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            throw new Error("Error adding post");
        }

        if (response.status === 200) {
            showToast("Post added successfully", "success", 3000);
        }

        cleanConstants();

        // eslint-disable-next-line @typescript-eslint/no-unused-vars
    } catch (error) {
        showToast("Error adding post", "error", 3000);
    }
}

function showAToast() {
    showToast("Hello");
}
</script>

<template>
    <div
        class="flex border rounded-2xl border-blue-300 shadow shrink max-w-150 w-full m-2 p-1"
    >
        <form
            class="flex flex-col w-full items-center"
            @submit.prevent="postForm"
        >
            <input
                v-model="postTitle"
                class="flex items-center w-full p-2 outline-hidden border-b-blue-300 border-b-1"
                placeholder="Post title..."
                required
            />
            <input
                v-model="postContent"
                class="flex items-center w-full p-2 outline-hidden"
                placeholder="Add a post... "
            />
            <div class="flex flex-row items-center justify-end w-full shrink">
                <button
                    class="bg-blue-500 text-white p-2 rounded-2xl m-1 cursor-pointer"
                    alt="Add Photo"
                    type="button"
                    @click="triggerFileInput"
                >
                    Add Photo
                    <i class="pi pi-image p-1"></i>
                </button>
                <input
                    class="hidden"
                    id="img"
                    type="file"
                    name="Select Image"
                    accept="image/jpeg"
                    ref="fileInputRef"
                    @change="handleFileChange"
                />
                <button
                    class="bg-blue-500 text-white p-2 rounded-2xl m-1 cursor-pointer"
                    value="submit"
                    type="submit"
                >
                    Post
                    <i class="pi pi-arrow-right p-1"></i>
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
