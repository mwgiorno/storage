<script setup>
    import { DocumentEditor } from "@onlyoffice/document-editor-vue";

    const props = defineProps({
        config: Object,
        serverUrl: String
    });

    props.config.height = '700px';

    function onDocumentReady() {
        console.log("Document is loaded");
    }

</script>

<template>
    <DocumentEditor 
        id="docEditor" 
        documentServerUrl="http://localhost:8080/"
        :config="config"
        :events_onDocumentReady="onDocumentReady"
    />
</template>
