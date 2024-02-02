<script setup>
    import { reactive } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router, useForm } from '@inertiajs/vue3';
    import Editor from '@/Components/OnlyOfficeEditor.vue';

    const props = defineProps({
        file: Object,
        config: Object,
        serverUrl: String
    });

</script>

<template>
    <AuthenticatedLayout>
        <Head title="View in OnlyOffice" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">View in OnlyOffice</h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Suspense>
                    <Editor :config="props.config" :server-url="serverUrl" />
                </Suspense>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
