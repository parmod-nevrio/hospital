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
    token: localStorage.getItem('token'),
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role,
    userPermissions: (state) => {
      console.log('User data in getter:', state.user);
      if (!state.user?.role?.permissions) return [];
      return state.user.role.permissions.map(p => p.key);
    }
  },

  actions: {
    // async initialize() {
    //   console.log('Initializing auth store with token:', this.token);
    //   if (this.token) {
    //     try {
    //       await this.fetchUser();
    //     } catch (error) {
    //       console.error('Failed to initialize auth store:', error);
    //       this.logout();
    //     }
    //   }
    // },

    async login(credentials) {
      try {
        this.loading = true;
        this.error = null;
        const response = await axios.post('/api/login', credentials);
        console.log('Login response:', response.data);
        this.token = response.data.data.token;
        this.user = response.data.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return true;
      } catch (error) {
        console.error('Login error:', error.response?.data);
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
        console.log('Register response:', response.data);
        this.token = response.data.data.token;
        this.user = response.data.data.user;
        localStorage.setItem('token', this.token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        return true;
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
        if (this.token) {
          await axios.post('/api/logout');
        }
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
        console.log('Fetching user profile...');
        const response = await axios.get('/api/profile');
        console.log('Profile response:', response.data);
        if (response.data.data && response.data.data.user) {
          this.user = response.data.data.user;
          console.log('User data set:', this.user);
        } else {
          console.error('Invalid profile response structure:', response.data);
          throw new Error('Invalid profile response structure');
        }
        return true;
      } catch (error) {
        console.error('Fetch user error:', error.response?.data);
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
        console.log('Update profile response:', response.data);
        if (response.data.data && response.data.data.user) {
          this.user = response.data.data.user;
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
    }
  }
});
