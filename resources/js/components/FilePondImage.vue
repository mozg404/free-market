<script setup>
import { ref, onMounted, watch } from 'vue';
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';

FilePond.registerPlugin(
  FilePondPluginImagePreview,
  FilePondPluginFilePoster,
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
);

const config = {
  uploadRoute: route('filepond.image.upload'),
  loadRoute: route('filepond.image.load'),
  removeRoute: route('filepond.image.remove'),
  acceptedFileTypes: ['image/*'],
  maxFileSize: '5MB',
}

const props = defineProps({
  modelValue: [String, Array, File, null],
  allowMultiple: {
    type: Boolean,
    default: false
  },
});

const emit = defineEmits(['update:modelValue']);

const filepond = ref(null);
let pond = null;

// Инициализация FilePondImage
onMounted(() => {
  pond = FilePond.create(filepond.value, {
    allowMultiple: props.allowMultiple,
    acceptedFileTypes: config.acceptedFileTypes,
    maxFileSize: config.maxFileSize,
    credits: false,
    labelIdle: 'Перетащите файлы или <span class="filepond--label-action">выберите</span>',
    labelFileProcessingComplete: 'Файл загружен',
    labelFileProcessingError: 'Ошибка загрузки',
    labelFileProcessing: 'Загрузка...',
    labelFileProcessingAborted: 'Загрузка отменена',
    labelTapToCancel: 'нажмите для отмены',
    labelTapToRetry: 'нажмите для повтора',
    labelTapToUndo: 'нажмите для отмены',
    labelButtonRemoveItem: 'Удалить',
    labelButtonAbortItemLoad: 'Отменить',
    labelButtonRetryItemLoad: 'Повторить',
    labelButtonAbortItemProcessing: 'Отменить',
    labelButtonUndoItemProcessing: 'Отменить',
    labelButtonRetryItemProcessing: 'Повторить',
    labelButtonProcessItem: 'Загрузить',
    labelMaxFileSizeExceeded: 'Файл слишком большой',
    labelMaxFileSize: 'Максимальный размер файла: {filesize}',
    labelFileTypeNotAllowed: 'Недопустимый тип файла',
    fileValidateTypeLabelExpectedTypes: 'Допустимые форматы: {allButLastType}, {lastType}',
    server: {
      process: {
        url: config.uploadRoute,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      },
      load: {
        url: config.loadRoute + '?path=',
      },
      revert: {
        url: config.removeRoute,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      }
    },
    onprocessfile: (error, file) => {
      if (!error) {
        updateModelValue(file.serverId);
      }
    },
    onremovefile: () => {
        updateModelValue(props.allowMultiple ? [] : null);
    }
  });

  if (props.modelValue) {
    pond.addFile(props.modelValue, {
        type: 'local',
    });
    updateModelValue(props.modelValue)
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
