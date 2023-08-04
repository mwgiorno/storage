<script setup>
import Modal from '@/Components/Modal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    file: Number
});

function deleteFile(file) {
    router.delete(route('files.destroy', file))
};
</script>

<template>
    <Modal :show="props.show">
        <form class="grid grid-cols-2 divide-x py-2 text-sm">
            <button type="button" @click="deleteFile(props.file)" class="w-full text-center py-2 text-slate-600 hover:text-indigo-500 font-bold">Confirm</button>
            <button type="button" @click="props.show = false" class="w-full text-center py-2 text-slate-600 hover:text-red-500 font-bold">Cancel</button>
        </form>
    </Modal>
</template>
