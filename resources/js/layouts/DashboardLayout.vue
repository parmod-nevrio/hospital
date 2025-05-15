<template>
  <div class="dashboard-layout">
    <!-- <AppSidebar /> -->
    <div class="main-content">
      <!-- <AppTopbar /> -->
      <div class="content-wrapper">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script setup>
import AppSidebar from '@/components/layout/AppSidebar.vue';
import AppTopbar from '@/components/layout/AppTopbar.vue';
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  background-color: #f8f9fa;
}

.content-wrapper {
  flex: 1;
  padding: 2rem;
  overflow-y: auto;
}
</style>
