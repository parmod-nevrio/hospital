<template>
  <div class="register-page">
    <Card class="register-card">
      <template #title>
        <h2 class="text-center">Register</h2>
      </template>
      <template #content>
        <form @submit.prevent="handleSubmit" class="p-fluid">
          <div class="field">
            <label for="name">Name</label>
            <InputText
              id="name"
              v-model="form.name"
              :class="{ 'p-invalid': submitted && !form.name }"
              required
            />
            <small class="p-error" v-if="submitted && !form.name">Name is required.</small>
          </div>

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
            <label for="phone">Phone</label>
            <InputText
              id="phone"
              v-model="form.phone"
              :class="{ 'p-invalid': submitted && !form.phone }"
              required
            />
            <small class="p-error" v-if="submitted && !form.phone">Phone is required.</small>
          </div>

          <div class="field">
            <label for="address">Address</label>
            <Textarea
              id="address"
              v-model="form.address"
              rows="3"
              :class="{ 'p-invalid': submitted && !form.address }"
              required
            />
            <small class="p-error" v-if="submitted && !form.address">Address is required.</small>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <Password
              id="password"
              v-model="form.password"
              :class="{ 'p-invalid': submitted && !form.password }"
              required
            />
            <small class="p-error" v-if="submitted && !form.password">Password is required.</small>
          </div>

          <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <Password
              id="password_confirmation"
              v-model="form.password_confirmation"
              :class="{ 'p-invalid': submitted && !form.password_confirmation }"
              required
            />
            <small class="p-error" v-if="submitted && !form.password_confirmation">
              Password confirmation is required.
            </small>
          </div>

          <Button type="submit" label="Register" class="mt-2" />
        </form>

        <div class="mt-4 text-center">
          <p>
            Already have an account?
            <router-link to="/login">Login here</router-link>
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
  name: '',
  email: '',
  phone: '',
  address: '',
  password: '',
  password_confirmation: ''
});

const submitted = ref(false);

const handleSubmit = async () => {
  submitted.value = true;

  if (
    form.name &&
    form.email &&
    form.phone &&
    form.address &&
    form.password &&
    form.password_confirmation
  ) {
    try {
      await authStore.register(form);
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Registration successful',
        life: 3000
      });
      router.push('/login');
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: error.response?.data?.message || 'Registration failed',
        life: 3000
      });
    }
  }
};
</script>

<style scoped>
.register-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f8f9fa;
}

.register-card {
  width: 100%;
  max-width: 500px;
  margin: 2rem;
}

.field {
  margin-bottom: 1.5rem;
}

:deep(.p-password input) {
  width: 100%;
}
</style>
