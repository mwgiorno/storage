<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import FileInput from '@/Components/FileInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import byteSize from 'byte-size';
import { ref } from 'vue';

const props = defineProps({
    file: Object
});

const form = useForm({
    _method: 'patch',
    name: props.file.name,
    file: null
});

let showDeleteConfirmation = ref(false);
let showSaveConfirmation = ref(false);

function deleteFile() {
    router.delete(route('files.destroy', {file: props.file.id}));
    showDeleteConfirmation.value = false;
}

function saveFile() {
    form.post(route('files.update', props.file.id), {
        onFinish: () => form.reset(),
    });
    showSaveConfirmation.value = false;
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit File" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit File</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div>
                        File: {{ file.name }}
                    </div>
                    <div>
                        Extension: {{ file.extension }}
                    </div>
                    <div>
                        Size: {{ byteSize(file.size) }}
                    </div>
                    <form @submit.prevent class="grid gap-y-3 mt-3">
                        <div>
                            <FileInput v-model="form.file" />
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>

                            <InputError class="mt-2" :message="form.errors.file" />
                        </div>

                        <div>
                            <TextInput
                                id="name"
                                type="text"
                                class="block w-full"
                                v-model="form.name"
                                placeholder="Name"
                            />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="flex items-center gap-x-2">
                            <button
                                type="button"
                                @click.prevent="showSaveConfirmation = true"
                                class="flex w-full justify-center rounded border border-2 hover:border-indigo-400 px-3 py-2 font-semibold leading-6 text-gray-700 shadow-sm hover:text-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Save
                            </button>
                            <button
                                type="button"
                                @click.prevent="showDeleteConfirmation = true"
                                class="flex w-full justify-center rounded bg-white px-3 py-2 font-semibold leading-6 text-gray-700 border border-2 shadow-sm hover:text-red-500 hover:border-red-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <ConfirmationModal :show="showSaveConfirmation" @confirmed="saveFile" @canceled="showSaveConfirmation = false"></ConfirmationModal>
        <ConfirmationModal :show="showDeleteConfirmation" @confirmed="deleteFile" @canceled="showDeleteConfirmation = false"></ConfirmationModal>
    </AuthenticatedLayout>
</template>
