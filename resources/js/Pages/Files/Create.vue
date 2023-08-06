<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import FileInput from '@/Components/FileInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    file: null
});

const submit = () => {
    form.post(route('files.store'), {
        onFinish: () => form.reset(),
    });
};
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
