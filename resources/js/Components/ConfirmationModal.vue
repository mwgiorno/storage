<script setup>
import Modal from '@/Components/Modal.vue';

const emit = defineEmits(['confirmed', 'canceled'])

const props = defineProps({
    show: Boolean
});

</script>

<template>
    <Modal :show="props.show">
        <form class="grid grid-cols-2 divide-x py-2 text-sm">
            <button type="button" @click="emit('confirmed')" class="w-full text-center py-2 text-slate-600 hover:text-indigo-500 font-bold">Confirm</button>
            <button type="button" @click="emit('canceled')" class="w-full text-center py-2 text-slate-600 hover:text-red-500 font-bold">Cancel</button>
        </form>
    </Modal>
</template>
