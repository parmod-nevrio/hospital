import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Layouts
import AuthLayout from '../layouts/AuthLayout.vue';
import DashboardLayout from '../layouts/DashboardLayout.vue';

// Auth Pages
import Login from '../views/auth/Login.vue';
import Register from '../views/auth/Register.vue';
import ForgotPassword from '../views/auth/ForgotPassword.vue';

// Dashboard Pages
import Dashboard from '../views/dashboard/Dashboard.vue';
import Profile from '../views/dashboard/Profile.vue';
import Users from '../views/dashboard/Users.vue';
import Roles from '../views/dashboard/Roles.vue';
import Permissions from '../views/dashboard/Permissions.vue';

// Role-specific routes will be added dynamically based on user role

const routes = [
  {
    path: '/',
    redirect: '/login',
    component: AuthLayout,
    children: [
      {
        path: 'login',
        name: 'login',
        component: Login,
        meta: { requiresGuest: true }
      },
      {
        path: 'register',
        name: 'register',
        component: Register,
        meta: { requiresGuest: true }
      },
      {
        path: 'forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
        meta: { requiresGuest: true }
      }
    ]
  },
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard',
        component: Dashboard,
        meta: { title: 'Dashboard' }
      },
      {
        path: 'profile',
        name: 'profile',
        component: Profile,
        meta: { title: 'Profile' }
      },
      {
        path: 'users',
        name: 'users',
        component: Users,
        meta: { title: 'User Management', requiresPermission: 'users.view' }
      },
      {
        path: 'roles',
        name: 'roles',
        component: Roles,
        meta: { title: 'Role Management', requiresPermission: 'roles.view' }
      },
      {
        path: 'permissions',
        name: 'permissions',
        component: Permissions,
        meta: { title: 'Permission Management', requiresPermission: 'roles.view' }
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresGuest = to.matched.some(record => record.meta.requiresGuest);
  const requiresPermission = to.meta.requiresPermission;
console.log(">>>>>>",authStore.userPermissions);
  if (requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' });
  } else if (requiresGuest && authStore.isAuthenticated) {
    next({ name: 'dashboard' });
  } else if (requiresPermission && !authStore.userPermissions.includes(requiresPermission)) {
    next({ name: 'dashboard' });
  } else {
    next();
  }
});

export default router;
