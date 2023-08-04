<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    file: null
});

let isDragging = ref(false);

const submit = () => {
    form.post(route('files.store'), {
        onFinish: () => form.reset(),
    });
};

function dropHandler(event) {
    event.preventDefault();
    form.file = event.dataTransfer.files[0];
    isDragging.value = false;
}

function dragOverHandler(event) {
    event.preventDefault();
    isDragging.value = true;
}

function dragLeaveHandler(event) {
    event.preventDefault();
    isDragging.value = false;
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create File" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create a new file</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="grid gap-y-3">
                        <div>
                            <label 
                                @drop="dropHandler"
                                @dragover="dragOverHandler"
                                @dragleave="dragLeaveHandler"
                                style="background-color: transparent;"
                                for="file"
                                class="w-full rounded outline-dashed bg-transparent flex justify-center items-center gap-x-1 text-gray-600 font-semibold outline-2 py-12 outline-gray-300 hover:outline-gray-400 cursor-pointer"
                                :class="{'outline-indigo-400': isDragging}"
                            >
                                <div v-if="!form.file">
                                    <i class="fa-regular fa-square-plus"></i>
                                    Upload a file
                                </div>
                                <div v-else>
                                    {{ form.file.name }}
                                </div>
                            </label>
                            <input
                                id="file"
                                type="file"
                                class="hidden"
                                @input="form.file = $event.target.files[0]"

                            />
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

                        <button
                            type="submit"
                            class="flex w-full justify-center rounded bg-indigo-500 px-3 py-2 font-semibold leading-6 text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
