<script setup lang="ts">
import Header from "./Header.vue";
import PostCard from "./PostCard.vue";
import AboutBio from "./AboutBio.vue";
import CreatePost from "./CreatePost.vue";
import { useTranslation } from "../composables/useTranslation";
import { onMounted, ref } from "vue";
import type { NewPostCreatedEvent, Post } from "../PostEvent.ts";
const { t } = useTranslation();
import { base64ToImage } from "../decodeToImage.ts";
const postArray = ref<Post[]>([]);

// Fetch all posts or fetch post by ID
async function fetchPost(id?: number) {
    try {
        const data = await fetch(id ? `/api/post/${id}` : "/api/post");
        const post = await data.json();
        addPostsToArray(post);
    } catch (error) {
        console.error(error);
    }
}

// Once posts fetched, add them to the array
function addPostsToArray(posts: Post[] | Post) {
    const newPosts = Array.isArray(posts) ? posts : [posts];
    for (const post of newPosts) {
        if (!postArray.value.find((p) => p.id === post.id)) {
            if (post.image) {
                post.image = base64ToImage(post.image);
            }
        }
        post.created_at = new Date(post.created_at).toLocaleString();
        postArray.value = [post, ...postArray.value];
    }
}

onMounted(() => {
    fetchPost();
    window.Echo.channel("diffEarth-channel").listen(
        "NewPostCreated",
        (event: NewPostCreatedEvent) => {
            fetchPost(parseInt(JSON.stringify(event.post)));
        },
    );
});
</script>

<template>
    <Header />
    <div class="flex flex-row mx-5 bg-[#FAFAFA] rounded-sm">
        <section class="w-3/5 shadow">
            <div class="flex justify-center">
                <h1
                    class="underline text-3xl font-bold font-sans italic text-gray-800"
                >
                    {{ t.aboutUs }}
                </h1>
            </div>

            <div class="flex flex-col items-center p-2">
                <CreatePost />
                <div v-for="post in postArray" :key="post.id">
                    <PostCard
                        class="p-2 m-2"
                        :imgMain="post.image"
                        :heading="post.title"
                        :content="post.content"
                        :name="post.user"
                        :date="post.created_at"
                    />
                </div>
            </div>
        </section>
        <aside class="w-2/5 ml-5">
            <AboutBio />
        </aside>
    </div>
</template>

<style scoped></style>
