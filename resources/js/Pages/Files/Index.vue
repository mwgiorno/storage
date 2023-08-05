<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    files: Object,
});

let targetFile = ref(null);

function deleteFile() {
    router.delete(route('files.destroy', {file: targetFile.value.id}));
    targetFile.value = null;
}
</script>

<template>
    <Head title="Files" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Files</h2>
                <Link
                    :href="route('files.create')"
                    class="inline-flex items-center rounded gap-x-1 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                >
                    <i class="fa-solid fa-plus"></i>
                    New File
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-4 mt-4 gap-4 items-start">
                    <div v-for="file in files" :key="file.id" class="bg-white shadow rounded overflow-hidden grid text-sm text-gray-700 divide-y">
                            <div class="grid gap-y-1 p-6">
                                <p class="mb-2">
                                    <span class="bg-indigo-100 text-indigo-500 px-3 py-1 rounded-full font-bold">
                                        {{ file.extension }}
                                    </span>
                                </p>
                                <p v-if="file.thumbnail_url" class="mx-auto">
                                    <img :src="file.thumbnail_url" class="object-cover">
                                </p>
                                <p>
                                    <a :href="route('files.download', file.id)" class="font-bold break-all hover:text-indigo-500">{{ file.name }}</a>
                                </p>
                                <p class="text-slate-500 font-semibold">
                                    {{ file.size }}
                                </p>
                            </div>
                            <div class="grid grid-cols-2 divide-x">
                                <Link :href="route('files.edit', file.id)" class="text-center py-2 text-slate-600 hover:text-indigo-600 font-bold"><i class="fa-solid fa-pen"></i> Edit</Link>
                                <button type="button" @click="targetFile = file" class="w-full text-center py-2 text-slate-600 hover:text-red-500 font-bold"><i class="fa-solid fa-trash"></i> Delete</button>
                            </div>
                    </div>
                    <Modal :show="Boolean(targetFile)">
                        <form class="grid grid-cols-2 divide-x py-2 text-sm">
                            <button type="button" @click="deleteFile()" class="w-full text-center py-2 text-slate-600 hover:text-indigo-500 font-bold">Confirm</button>
                            <button type="button" @click="targetFile = null" class="w-full text-center py-2 text-slate-600 hover:text-red-500 font-bold">Cancel</button>
                        </form>
                    </Modal>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
