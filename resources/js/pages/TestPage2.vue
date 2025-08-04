<script setup>
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu'
import { Button } from '@/components/ui/button'
import { Separator } from '@/components/ui/separator'
import { Home, Settings, Users } from 'lucide-vue-next'

const menuItems = [
  { title: 'Главная', route: 'dashboard', icon: Home },
  { title: 'Пользователи', route: 'users.index', icon: Users },
  { title: 'Настройки', route: 'settings', icon: Settings }
]
</script>

<template>
  <NavigationMenu class="w-full">
    <NavigationMenuList class="flex-col items-start gap-1">
      <NavigationMenuItem v-for="item in menuItems" :key="item.title">
        <NavigationMenuLink :href="item.route" as-child>
          <Button variant="ghost" class="w-full justify-start">
            <component :is="item.icon" class="mr-2 h-4 w-4" />
            {{ item.title }}
          </Button>
        </NavigationMenuLink>
      </NavigationMenuItem>

      <Separator class="my-2" />

      <!-- Дополнительные пункты -->
    </NavigationMenuList>
  </NavigationMenu>
</template>