<template>
  <div>
    <div class="quill-editor">
      <QuillEditor
        ref="quill"
        v-model:content="content"
        contentType="html"
        :options="editorOptions"
        @ready="onEditorReady"
        @update:content="onContentUpdate"
      />
    </div>
  </div>
</template>

<script setup>
import { QuillEditor } from '@vueup/vue-quill';
import 'quill/dist/quill.snow.css'; // Стили Snow-темы
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: 'Введите текст...',
  },
});

const emit = defineEmits(['update:modelValue']);

const content = ref(props.modelValue);
const quill = ref(null);

// Настройки редактора
const editorOptions = {
  placeholder: props.placeholder,
  modules: {
    toolbar: [
      ['bold', 'italic', 'underline', 'strike'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['clean'],
    ],
    clipboard: {
      // matchVisual: false, // Отключаем визуальное форматирование
      matchers: [
        ['*', (node, delta) => {
          // Оставляем только чистый текст
          const plainText = node.textContent;
          return {
            ops: [{ insert: plainText }]
          };
        }]
      ]
    }
  },
  theme: 'snow', // Snow — стандартная тема Quill
};

// Обновляем модель при изменении контента
const onContentUpdate = (newContent) => {
  emit('update:modelValue', newContent);
};

// Если нужно выполнить действия после инициализации
const onEditorReady = (editor) => {
  console.log('Редактор готов!', editor);
};

// Следим за внешними изменениями modelValue
watch(
  () => props.modelValue,
  (newValue) => {
    if (newValue !== content.value) {
      content.value = newValue;
    }
  }
);
</script>

<style>
/* Кастомизация под Tailwind */

</style>