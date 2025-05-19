import { defineStore } from 'pinia';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

// Set up axios defaults
if (localStorage.getItem('token')) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
    userRole: (state) => state.user?.role,
    userPermissions: (state) => {
      console.log('User data in getter:', state.user);
      if (!state.user?.role?.permissions) return [];
      return state.user.role.permissions.map(p => p.key);
    }
  },

  actions: {
    async initialize() {
      console.log('Initializing auth store with token:', this.token);
      if (this.token) {
        try {
          await this.fetchUser();
        } catch (error) {
          console.error('Failed to initialize auth store:', error);
          this.logout();
        }
      }
    },

    async login(credentials) {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.post('/login', credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        localStorage.setItem('token', response.data.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return response;
      } catch (error) {
        this.error = error.response?.data?.message || 'An error occurred during login';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async register(userData) {
      try {
        this.loading = true;
        this.error = null;
        const response = await axios.post('/api/register', userData);
        console.log('Register response:', response.data);
        this.token = response.data.token;
        this.user = response.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return response;
      } catch (error) {
        console.error('Register error:', error.response?.data);
        this.error = error.response?.data?.message || 'An error occurred';
        return false;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await axios.post('/logout');
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
      } catch (error) {
        console.error('Logout error:', error);
      }
    },

    async fetchUser() {
      try {
        const response = await axios.get('/user');
        this.user = response.data;
      } catch (error) {
        this.error = error.response?.data?.message;
        throw error;
      }
    },

    async updateProfile(profileData) {
      try {
        this.loading = true;
        const response = await axios.put('/api/profile', profileData);
        console.log('Update profile response:', response.data);
        if (response.data && response.data.user) {
          this.user = response.data.user;
        } else {
          console.error('Invalid update profile response structure:', response.data);
          throw new Error('Invalid update profile response structure');
        }
        return true;
      } catch (error) {
        console.error('Update profile error:', error.response?.data);
        this.error = error.response?.data?.message || 'An error occurred';
        return false;
      } finally {
        this.loading = false;
      }
    },

    setUser(user) {
      this.user = user;
    },

    setToken(token) {
      this.token = token;
      localStorage.setItem('token', token);
    },

    clearError() {
      this.error = null;
    }
  }
});
