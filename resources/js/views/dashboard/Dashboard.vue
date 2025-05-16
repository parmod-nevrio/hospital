<template>
  <div class="dashboard">
    <Card>
      <template #title>
        <h2>Dashboard</h2>
      </template>
      <template #content>
        <p>Welcome to your dashboard!</p>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/login');
  }
});
</script>

<style scoped>
.dashboard {
  padding: 2rem;
}
</style>
