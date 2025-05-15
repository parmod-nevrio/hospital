<template>
  <div class="sidebar" :class="{ 'sidebar-collapsed': isCollapsed }">
    <div class="sidebar-header">
      <img src="/logo.png" alt="Hospital Logo" class="logo" />
      <Button
        v-if="!isCollapsed"
        icon="pi pi-angle-left"
        class="p-button-text collapse-btn"
        @click="toggleSidebar"
      />
      <Button
        v-else
        icon="pi pi-angle-right"
        class="p-button-text collapse-btn"
        @click="toggleSidebar"
      />
    </div>

    <div class="sidebar-content">
      <Menu :model="menuItems" class="sidebar-menu" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Menu from 'primevue/menu';
import Button from 'primevue/button';

const router = useRouter();
const authStore = useAuthStore();
const isCollapsed = ref(false);

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
};

const menuItems = [
  {
    label: 'Dashboard',
    icon: 'pi pi-home',
    command: () => router.push({ name: 'dashboard' })
  },
  {
    label: 'User Management',
    icon: 'pi pi-users',
    items: [
      {
        label: 'Users',
        icon: 'pi pi-user',
        command: () => router.push({ name: 'users' })
      },
      {
        label: 'Roles',
        icon: 'pi pi-shield',
        command: () => router.push({ name: 'roles' })
      },
      {
        label: 'Permissions',
        icon: 'pi pi-lock',
        command: () => router.push({ name: 'permissions' })
      }
    ]
  },
  {
    label: 'Patients',
    icon: 'pi pi-heart',
    command: () => router.push({ name: 'patients' })
  },
  {
    label: 'Appointments',
    icon: 'pi pi-calendar',
    command: () => router.push({ name: 'appointments' })
  },
  {
    label: 'Doctors',
    icon: 'pi pi-user-plus',
    command: () => router.push({ name: 'doctors' })
  },
  {
    label: 'Departments',
    icon: 'pi pi-building',
    command: () => router.push({ name: 'departments' })
  },
  {
    label: 'Settings',
    icon: 'pi pi-cog',
    command: () => router.push({ name: 'settings' })
  }
];
</script>

<style scoped>
.sidebar {
  width: 280px;
  height: 100vh;
  background-color: #ffffff;
  border-right: 1px solid #e9ecef;
  transition: width 0.3s ease;
  display: flex;
  flex-direction: column;
}

.sidebar-collapsed {
  width: 80px;
}

.sidebar-header {
  height: 64px;
  padding: 0 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #e9ecef;
}

.logo {
  height: 40px;
  width: auto;
}

.collapse-btn {
  color: #64748b;
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 1rem 0;
}

.sidebar-menu {
  border: none;
  width: 100%;
}

:deep(.p-menu) {
  border: none;
  width: 100%;
}

:deep(.p-menu .p-menuitem-link) {
  padding: 0.75rem 1rem;
  color: #1e293b;
}

:deep(.p-menu .p-menuitem-link:hover) {
  background-color: #f8f9fa;
}

:deep(.p-menu .p-menuitem-link .p-menuitem-icon) {
  color: #64748b;
  margin-right: 0.5rem;
}

:deep(.p-menu .p-menuitem-link .p-menuitem-text) {
  color: #1e293b;
}

:deep(.p-menu .p-submenu-header) {
  padding: 0.75rem 1rem;
  color: #64748b;
  font-weight: 600;
}

:deep(.p-menu .p-submenu-list) {
  padding: 0.5rem 0;
}

:deep(.p-menu .p-submenu-list .p-menuitem-link) {
  padding: 0.5rem 1rem 0.5rem 2.5rem;
}

.sidebar-collapsed :deep(.p-menu .p-menuitem-text),
.sidebar-collapsed :deep(.p-menu .p-submenu-header) {
  display: none;
}

.sidebar-collapsed :deep(.p-menu .p-menuitem-link) {
  padding: 0.75rem;
  justify-content: center;
}

.sidebar-collapsed :deep(.p-menu .p-menuitem-link .p-menuitem-icon) {
  margin-right: 0;
}
</style>
