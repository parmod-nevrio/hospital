<template>
  <div class="login-page">
    <Card class="login-card">
      <template #title>
        <h2 class="text-center">Login</h2>
      </template>
      <template #content>
        <form @submit.prevent="handleSubmit" class="p-fluid">
          <div class="field">
            <label for="email">Email</label>
            <InputText
              id="email"
              v-model="form.email"
              type="email"
              :class="{ 'p-invalid': submitted && !form.email }"
              required
            />
            <small class="p-error" v-if="submitted && !form.email">Email is required.</small>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <Password
              id="password"
              v-model="form.password"
              :feedback="false"
              toggleMask
              :class="{ 'p-invalid': submitted && !form.password }"
              required
            />
            <small class="p-error" v-if="submitted && !form.password">Password is required.</small>
          </div>

          <div class="field-checkbox">
            <Checkbox id="remember" v-model="form.remember" :binary="true" />
            <label for="remember">Remember me</label>
          </div>

          <Button type="submit" label="Login" class="mt-2" />
        </form>

        <div class="mt-4 text-center">
          <p>
            Don't have an account?
            <router-link to="/register">Register here</router-link>
          </p>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const form = reactive({
  email: '',
  password: '',
  remember: false
});

const submitted = ref(false);

const handleSubmit = async () => {
  submitted.value = true;

  if (form.email && form.password) {
    try {
      await authStore.login(form);
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Login successful',
        life: 3000
      });
      router.push('/dashboard');
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: error.response?.data?.message || 'Login failed',
        life: 3000
      });
    }
  }
};
</script>

<style scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f8f9fa;
}

.login-card {
  width: 100%;
  max-width: 400px;
  margin: 2rem;
}

.field {
  margin-bottom: 1.5rem;
}

.field-checkbox {
  margin-bottom: 1.5rem;
}

:deep(.p-password input) {
  width: 100%;
}
</style>
