<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import byteSize from 'byte-size';
import { watch } from 'vue';

const props = defineProps({
    files: {
        type: Object,
        default: () => ({}),
    },
    total: Number,
    count: Number
});

let deleteTarget = ref(null);

let search = ref('');
watch(search, (value) => {
  router.get(
    "/files",
    { search: value },
    {
      preserveState: true,
    }
  );
}); 

function deleteFile() {
    router.delete(route('files.destroy', {file: deleteTarget.value.id}));
    deleteTarget.value = null;
}
</script>

<template>
    <Head title="Files" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Files
                    <span class="text-slate-500">{{ count }}/{{ total }}</span>
                </h2>
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
                <form @submit.prevent="search">
                    <input 
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="search"
                        name="search"
                        placeholder="Search"
                        v-model="search"
                    >
                </form>

                <div class="grid grid-cols-4 mt-4 gap-4 items-start">
                    <div v-for="file in props.files.data" :key="file.id" class="bg-white shadow rounded overflow-hidden grid text-sm text-gray-700 divide-y">
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
                                    {{ byteSize(file.size) }}
                                </p>
                                <p class="mt-2" v-if="file.pdfConvertible">
                                    <a :href="route('onlyoffice.download', {'file': file.id})" class="text-center text-red-400 hover:text-red-500 font-bold">
                                        Download PDF version
                                    </a>
                                </p>
                            </div>
                            <div :class="file.viewable ? 'grid grid-cols-3 divide-x' : 'grid grid-cols-2 divide-x'">
                                <Link :href="route('files.edit', file.id)" class="text-center py-2 text-slate-600 hover:text-indigo-600 font-bold"><i class="fa-solid fa-pen"></i> Edit</Link>
                                <Link v-if="file.viewable" :href="route('files.show', file.id)" class="text-center py-2 text-slate-600 hover:text-indigo-600 font-bold"><i class="fa-solid fa-eye"></i> View</Link>
                                <button type="button" @click="deleteTarget = file" class="w-full text-center py-2 text-slate-600 hover:text-red-500 font-bold"><i class="fa-solid fa-trash"></i> Delete</button>
                            </div>
                    </div>
                    
                    <ConfirmationModal :show="Boolean(deleteTarget)" @confirmed="deleteFile" @canceled="deleteTarget = null"></ConfirmationModal>
                </div>

                <Pagination :data="props.files"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
