<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="text-center mb-4">Hospital Management System</h2>
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <InputText
            id="email"
            v-model="email"
            type="email"
            class="w-full"
            :class="{ 'p-invalid': v$.email.$error }"
            aria-describedby="email-help"
          />
          <small v-if="v$.email.$error" class="p-error">
            {{ v$.email.$errors[0].$message }}
          </small>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <Password
            id="password"
            v-model="password"
            :feedback="false"
            toggleMask
            class="w-full"
            :class="{ 'p-invalid': v$.password.$error }"
          />
          <small v-if="v$.password.$error" class="p-error">
            {{ v$.password.$errors[0].$message }}
          </small>
        </div>

        <div class="flex justify-content-between align-items-center mb-4">
          <div class="flex align-items-center">
            <Checkbox
              v-model="rememberMe"
              :binary="true"
              inputId="rememberMe"
            />
            <label for="rememberMe" class="ml-2">Remember me</label>
          </div>
          <router-link to="/forgot-password" class="text-primary">
            Forgot Password?
          </router-link>
        </div>

        <Button
          type="submit"
          label="Login"
          class="w-full"
          :loading="authStore.loading"
        />

        <div class="text-center mt-4">
          <p>
            Don't have an account?
            <router-link to="/register" class="text-primary">Register</router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useVuelidate } from '@vuelidate/core';
import { required, email as emailValidator } from '@vuelidate/validators';
import { useToast } from 'primevue/usetoast';

// Components
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';

const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();

const email = ref('');
const password = ref('');
const rememberMe = ref(false);

const rules = {
  email: { required, email: emailValidator },
  password: { required }
};

const v$ = useVuelidate(rules, { email, password });

const handleLogin = async () => {
  const isValid = await v$.value.$validate();
  if (!isValid) return;

  const success = await authStore.login({
    email: email.value,
    password: password.value,
    remember: rememberMe.value
  });

  if (success) {
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Login successful',
      life: 3000
    });
    router.push({ name: 'dashboard' });
  } else {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: authStore.error || 'Login failed',
      life: 3000
    });
  }
};
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f8f9fa;
}

.login-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.mb-3 {
  margin-bottom: 1rem;
}

.mb-4 {
  margin-bottom: 1.5rem;
}

.mt-4 {
  margin-top: 1.5rem;
}

.w-full {
  width: 100%;
}

.text-center {
  text-align: center;
}

.text-primary {
  color: var(--primary-color);
  text-decoration: none;
}

.text-primary:hover {
  text-decoration: underline;
}
</style>
