<script setup>
import { SidebarProvider, SidebarTrigger, Sidebar } from '@/components/ui/sidebar'
import Wrapper from "@/components/core/layout/Wrapper.vue";
</script>

<template>
  <Wrapper class="relative">



  <SidebarProvider>
    <Sidebar>
      <slot name="sidebar" />
    </Sidebar>
    <main>
      <SidebarTrigger />
      <slot />
    </main>
  </SidebarProvider>
  </Wrapper>
</template>