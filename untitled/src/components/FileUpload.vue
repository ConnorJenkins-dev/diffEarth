<script setup>
import { ref } from 'vue'

const showModal = ref(false)
const selectedFile = ref(null)

function openModal() {
	showModal.value = true
}

function closeModal() {
	showModal.value = false
}

function onFileChange(event) {
	selectedFile.value = event.target.files[0]
}

function handleUpload() {
	console.log('Selected file:', selectedFile.value)

	showModal.value = false
}
</script>

<template>
	<div>
		<button @click="openModal" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white px-1 border border-blue-500 hover:border-transparent rounded">upload csv</button>

		<transition name="modal">
			<div v-if="showModal" class="fixed inset-0 bg-gray-800/50 flex items-center justify-center" @click.self="closeModal">
				<div class="bg-white rounded shadow-lg w-1/3 p-6 relative">
					<button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" @click="closeModal">&#10006;</button>
					<h2 class="text-xl font-bold mb-4">Upload File</h2>

					<form @submit.prevent="handleUpload">
						<label class="block mb-2 text-sm font-medium text-gray-700">
							Choose a file to upload
							<input type="file" class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded" @change="onFileChange" />
						</label>

						<div class="flex justify-end space-x-2">
							<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
							<button type="button" @click="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancel</button>
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