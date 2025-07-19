<script setup>
import { ref, onMounted, watch } from 'vue';
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';

// Регистрация плагинов
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFilePoster,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
);

const props = defineProps({
    modelValue: [String, Array, File, null], // v-model значение
    allowMultiple: {
        type: Boolean,
        default: false
    },
    acceptedFileTypes: {
        type: Array,
        default: () => ['image/*']
    },
    maxFileSize: {
        type: String,
        default: '5MB'
    },
    inject: {
        type: [Array, Object, null],
        default: () => null
    }
});

const emit = defineEmits(['update:modelValue']);

const filepond = ref(null);
let pond = null;

// Инициализация FilePond
onMounted(() => {
    pond = FilePond.create(filepond.value, {
        allowMultiple: props.allowMultiple,
        acceptedFileTypes: props.acceptedFileTypes,
        maxFileSize: props.maxFileSize,
        credits: false,
        server: {
            process: {
                url: route('uploader.upload'),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            },
            load: {
                url: route('uploader.load') + '?path=',
            },
            revert: (uniqueFileId, load) => {
                fetch(route('uploader.delete'), {
                    method: 'DELETE',
                    body: uniqueFileId
                }).then(load);
            }
        },
        onprocessfile: (error, file) => {
            if (!error) {
                updateModelValue(file.serverId);
                // updateModelValue(file.id);
            }
        },
        onremovefile: () => {
            updateModelValue(props.allowMultiple ? [] : null);
        }
    });

    if (!props.allowMultiple && props.inject) {
        pond.addFile(props.inject.path, {
            type: 'local',
        });
        updateModelValue(props.inject.path)
    }
});

// Обновление v-model
const updateModelValue = (value) => {
    if (props.allowMultiple) {
        const currentFiles = Array.isArray(props.modelValue) ? props.modelValue : [];
        emit('update:modelValue', [...currentFiles, value]);
    } else {
        emit('update:modelValue', value);
    }
};

// Следим за изменениями modelValue
watch(() => props.modelValue, (newVal) => {
    if (!newVal) pond.removeFiles();
});
</script>

<template>
    <input type="file" ref="filepond" :multiple="allowMultiple" />
</template>

<style scoped>

</style>
