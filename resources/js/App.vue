<template>
  <div class="app-container">
    <Toast />
    <div v-if="isAuthenticated" class="layout-wrapper">
      <AppSidebar />
      <div class="layout-main">
        <AppTopbar />
        <div class="layout-content">
          <router-view></router-view>
        </div>
      </div>
    </div>
    <div v-else>
      <router-view></router-view>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Toast from 'primevue/toast';
import AppSidebar from './components/layout/AppSidebar.vue';
import AppTopbar from './components/layout/AppTopbar.vue';

const authStore = useAuthStore();
const isAuthenticated = computed(() => authStore.isAuthenticated);

onMounted(async () => {
  await authStore.initialize();
});
</script>

<style>
.app-container {
  min-height: 100vh;
}

.layout-wrapper {
  display: flex;
  min-height: 100vh;
}

.layout-main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.layout-content {
  flex: 1;
  padding: 2rem;
  background-color: #f8f9fa;
}
</style>
