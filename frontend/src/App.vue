<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import MainLayout from '@/components/layout/MainLayout.vue'

const auth = useAuthStore()

onMounted(async () => {
  await auth.initializeAuth()
})
</script>

<template>
  <MainLayout>
    <router-view v-slot="{ Component }">
      <transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <component :is="Component" />
      </transition>
    </router-view>
  </MainLayout>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
