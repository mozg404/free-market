<script setup>
import {useForm} from '@inertiajs/vue3'
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import Main from "@/components/shared/layout/Main.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";
import ImageUploader from "@/components/shared/ImageUploader.vue";
import ErrorMessage from "@/components/support/ErrorMessage.vue";
import {Button} from "@/components/ui/button/index.js";

const props = defineProps({
  user: Object,
})

const form = useForm({
  avatar: props.user.avatar,
  _method: 'PATCH', // Method spoofing
});

const changeAvatar = () => {
  return form.post(route('my.settings.change-avatar', props.id), {
    forceFormData: true,
    onSuccess: () => {
      props.user.avatar = form.avatar;
    }
  })
}
</script>

<template>
  <MainLayout>
    <Wrapper>
      <Main>

        <div class="flex justify-between">
          <div class="flex-1">
            <MainTitle class="p-0">{{ user.name }}</MainTitle>
          </div>
          <div class="text-right">
            <form @submit.prevent="changeAvatar">
              <ImageUploader class="w-56" upload-label="Выбрать аватар" :aspect-ratio="1" v-model="form.avatar"/>
              <ErrorMessage :message="form.errors.avatar" />

              <div v-if="form.avatar !== props.user.avatar" class="text-center mt-4">
                <Button type="submit">Сохранить</Button>
              </div>
            </form>
          </div>
        </div>

      </Main>
    </Wrapper>
  </MainLayout>
</template>
