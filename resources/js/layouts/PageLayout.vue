<script setup>
import {Badge} from "@/components/ui/badge/index.js";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import PageTitle from "@/components/shared/layout/PageTitle.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import AppHeader from "@/layouts/parts/AppHeader.vue";
import AppBreadcrumbs from "@/layouts/parts/AppBreadcrumbs.vue";

const props = defineProps({
  withBreadcrumbs: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <AppLayout>
    <slot name="before-header"/>
    <AppHeader/>
    <AppBreadcrumbs v-if="withBreadcrumbs" />

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

  </AppLayout>
</template>