<template>
  <header class="topbar">
    <div class="left-section">
      <h2>{{ pageTitle }}</h2>
    </div>

    <div class="right-section">
      <Button
        type="button"
        icon="pi pi-bell"
        badge="3"
        class="notification-button p-button-text"
        @click="toggleNotifications"
        aria-haspopup="true"
        aria-controls="notifications_menu"
      />
      <Menu
        id="notifications_menu"
        ref="notificationsMenu"
        :model="notifications"
        :popup="true"
      />

      <div class="user-profile">
        <Button
          type="button"
          class="p-button-text"
          @click="toggleUserMenu"
          aria-haspopup="true"
          aria-controls="profile_menu"
        >
          <img
            :src="userAvatar"
            :alt="userName"
            class="user-avatar"
          />
          <span class="user-name">{{ userName }}</span>
          <i class="pi pi-angle-down" />
        </Button>
        <Menu
          id="profile_menu"
          ref="userMenu"
          :model="userMenuItems"
          :popup="true"
        />
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Button from 'primevue/button';
import Menu from 'primevue/menu';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const notificationsMenu = ref();
const userMenu = ref();

const userName = computed(() => authStore.user?.name || 'User');
const userAvatar = computed(() => authStore.user?.avatar || '/default-avatar.png');

const pageTitle = computed(() => {
  return route.meta.title || route.name || 'Dashboard';
});

const toggleNotifications = (event) => {
  notificationsMenu.value.toggle(event);
};

const toggleUserMenu = (event) => {
  userMenu.value.toggle(event);
};

const notifications = [
  {
    label: 'New Appointment Request',
    icon: 'pi pi-calendar',
    command: () => {
      router.push({ name: 'appointments' });
    }
  },
  {
    label: 'Lab Results Ready',
    icon: 'pi pi-file',
    command: () => {
      router.push({ name: 'lab-results' });
    }
  },
  {
    label: 'Message from Dr. Smith',
    icon: 'pi pi-envelope',
    command: () => {
      router.push({ name: 'messages' });
    }
  }
];

const userMenuItems = [
  {
    label: 'Profile',
    icon: 'pi pi-user',
    command: () => {
      router.push({ name: 'profile' });
    }
  },
  {
    label: 'Settings',
    icon: 'pi pi-cog',
    command: () => {
      router.push({ name: 'settings' });
    }
  },
  {
    separator: true
  },
  {
    label: 'Logout',
    icon: 'pi pi-sign-out',
    command: async () => {
      await authStore.logout();
      router.push({ name: 'login' });
    }
  }
];
</script>

<style scoped>
.topbar {
  height: 64px;
  padding: 0 2rem;
  background-color: #ffffff;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.left-section h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #1e293b;
}

.right-section {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.notification-button {
  position: relative;
}

.user-profile {
  display: flex;
  align-items: center;
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  margin-right: 0.5rem;
}

.user-name {
  margin-right: 0.5rem;
  color: #1e293b;
}

:deep(.p-button.p-button-text) {
  color: #1e293b;
}

:deep(.p-button.p-button-text:enabled:hover) {
  background: #f8f9fa;
  color: #1e293b;
}
</style>
