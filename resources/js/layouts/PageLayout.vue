<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import {Badge} from "@/components/ui/badge/index.js";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import PageTitle from "@/components/shared/layout/PageTitle.vue";

const props = defineProps({
  withBreadcrumbs: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <MainLayout :with-breadcrumbs="withBreadcrumbs">
    <div class="py-12">
      <Wrapper v-if="$slots.title">
        <div class="mb-12">
          <div class="flex items-center">
            <PageTitle v-if="$slots.title" >
              <slot name="title"/>
            </PageTitle>

            <Badge v-if="$slots.counter" variant="secondary" class="ml-4 py-1.5 px-2.5">
              <slot name="counter"/>
            </Badge>
          </div>
        </div>
      </Wrapper>

      <slot/>
    </div>
  </MainLayout>
</template>