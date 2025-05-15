import { defineStore } from 'pinia';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role,
    userPermissions: (state) => {
      if (!state.user?.role?.permissions) return [];
      return state.user.role.permissions.map(p => p.key);
    }
  },

  actions: {
    async login(credentials) {
      try {
        this.loading = true;
        this.error = null;
        const response = await axios.post('/api/login', credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

        //router.push({ name: 'dashboard' });
        return true;

      } catch (error) {
        this.error = error.response?.data?.message || 'An error occurred';
        return false;
      } finally {
        this.loading = false;
      }
    },

    async register(userData) {
      try {
        this.loading = true;
        this.error = null;
        const response = await axios.post('/api/register', userData);
        this.token = response.data.token;
        this.user = response.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'An error occurred';
        return false;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post('/api/logout');
      } catch (error) {
        console.error('Logout error:', error);
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
      }
    },

    async fetchUser() {
      try {
        this.loading = true;
        const response = await axios.get('/api/profile');
        this.user = response.data;
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'An error occurred';
        return false;
      } finally {
        this.loading = false;
      }
    },

    async updateProfile(profileData) {
      try {
        this.loading = true;
        const response = await axios.put('/api/profile', profileData);
        this.user = response.data;
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'An error occurred';
        return false;
      } finally {
        this.loading = false;
      }
    }
  }
});
