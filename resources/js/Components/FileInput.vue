<script setup>
import { ref } from 'vue';

defineProps({
    modelValue: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);

let isDragging = ref(false);

function dropHandler(event) {
    event.preventDefault();
    emit('update:modelValue', event.dataTransfer.files[0]);
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
            <div v-if="modelValue">
                {{ modelValue.name }}
            </div>
            <div v-else>
                <i class="fa-regular fa-square-plus"></i>
                Upload a file
            </div>
        </label>
        <input
            id="file"
            type="file"
            class="hidden"
            @input="emit('update:modelValue', $event.target.files[0])"
        />
    </div>
</template>
