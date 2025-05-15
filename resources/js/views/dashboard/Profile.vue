<template>
  <div class="profile">
    <div class="grid">
      <!-- Profile Information -->
      <div class="col-12 lg:col-4">
        <Card>
          <template #title>Profile Information</template>
          <template #content>
            <div class="profile-header">
              <div class="avatar-wrapper">
                <img :src="userAvatar" :alt="user.name" class="avatar" />
                <Button
                  icon="pi pi-camera"
                  class="avatar-upload"
                  @click="triggerFileUpload"
                />
                <input
                  type="file"
                  ref="fileInput"
                  accept="image/*"
                  class="hidden"
                  @change="handleAvatarChange"
                />
              </div>
              <h2>{{ user.name }}</h2>
              <p>{{ user.role }}</p>
            </div>

            <div class="profile-info">
              <div class="info-item">
                <i class="pi pi-envelope"></i>
                <span>{{ user.email }}</span>
              </div>
              <div class="info-item">
                <i class="pi pi-phone"></i>
                <span>{{ user.phone || 'Not provided' }}</span>
              </div>
              <div class="info-item">
                <i class="pi pi-map-marker"></i>
                <span>{{ user.address || 'Not provided' }}</span>
              </div>
            </div>
          </template>
        </Card>
      </div>

      <!-- Edit Profile -->
      <div class="col-12 lg:col-8">
        <Card>
          <template #title>Edit Profile</template>
          <template #content>
            <form @submit.prevent="handleSubmit" class="p-fluid">
              <div class="grid">
                <div class="col-12 md:col-6">
                  <div class="field">
                    <label for="firstName">First Name</label>
                    <InputText
                      id="firstName"
                      v-model="form.firstName"
                      :class="{ 'p-invalid': v$.form.firstName.$error }"
                    />
                    <small v-if="v$.form.firstName.$error" class="p-error">
                      {{ v$.form.firstName.$errors[0].$message }}
                    </small>
                  </div>
                </div>

                <div class="col-12 md:col-6">
                  <div class="field">
                    <label for="lastName">Last Name</label>
                    <InputText
                      id="lastName"
                      v-model="form.lastName"
                      :class="{ 'p-invalid': v$.form.lastName.$error }"
                    />
                    <small v-if="v$.form.lastName.$error" class="p-error">
                      {{ v$.form.lastName.$errors[0].$message }}
                    </small>
                  </div>
                </div>

                <div class="col-12">
                  <div class="field">
                    <label for="email">Email</label>
                    <InputText
                      id="email"
                      v-model="form.email"
                      type="email"
                      :class="{ 'p-invalid': v$.form.email.$error }"
                    />
                    <small v-if="v$.form.email.$error" class="p-error">
                      {{ v$.form.email.$errors[0].$message }}
                    </small>
                  </div>
                </div>

                <div class="col-12">
                  <div class="field">
                    <label for="phone">Phone</label>
                    <InputText
                      id="phone"
                      v-model="form.phone"
                      :class="{ 'p-invalid': v$.form.phone.$error }"
                    />
                    <small v-if="v$.form.phone.$error" class="p-error">
                      {{ v$.form.phone.$errors[0].$message }}
                    </small>
                  </div>
                </div>

                <div class="col-12">
                  <div class="field">
                    <label for="address">Address</label>
                    <Textarea
                      id="address"
                      v-model="form.address"
                      rows="4"
                      :class="{ 'p-invalid': v$.form.address.$error }"
                    />
                    <small v-if="v$.form.address.$error" class="p-error">
                      {{ v$.form.address.$errors[0].$message }}
                    </small>
                  </div>
                </div>
              </div>

              <div class="flex justify-content-end">
                <Button
                  type="submit"
                  label="Save Changes"
                  icon="pi pi-check"
                  :loading="loading"
                />
              </div>
            </form>
          </template>
        </Card>

        <!-- Change Password -->
        <Card class="mt-4">
          <template #title>Change Password</template>
          <template #content>
            <form @submit.prevent="handlePasswordChange" class="p-fluid">
              <div class="field">
                <label for="currentPassword">Current Password</label>
                <Password
                  id="currentPassword"
                  v-model="passwordForm.currentPassword"
                  :feedback="false"
                  toggleMask
                  :class="{ 'p-invalid': v$.passwordForm.currentPassword.$error }"
                />
                <small v-if="v$.passwordForm.currentPassword.$error" class="p-error">
                  {{ v$.passwordForm.currentPassword.$errors[0].$message }}
                </small>
              </div>

              <div class="field">
                <label for="newPassword">New Password</label>
                <Password
                  id="newPassword"
                  v-model="passwordForm.newPassword"
                  toggleMask
                  :class="{ 'p-invalid': v$.passwordForm.newPassword.$error }"
                />
                <small v-if="v$.passwordForm.newPassword.$error" class="p-error">
                  {{ v$.passwordForm.newPassword.$errors[0].$message }}
                </small>
              </div>

              <div class="field">
                <label for="confirmPassword">Confirm New Password</label>
                <Password
                  id="confirmPassword"
                  v-model="passwordForm.confirmPassword"
                  :feedback="false"
                  toggleMask
                  :class="{ 'p-invalid': v$.passwordForm.confirmPassword.$error }"
                />
                <small v-if="v$.passwordForm.confirmPassword.$error" class="p-error">
                  {{ v$.passwordForm.confirmPassword.$errors[0].$message }}
                </small>
              </div>

              <div class="flex justify-content-end">
                <Button
                  type="submit"
                  label="Change Password"
                  icon="pi pi-lock"
                  :loading="passwordLoading"
                />
              </div>
            </form>
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength, sameAs } from '@vuelidate/validators';
import { useToast } from 'primevue/usetoast';

// Components
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Password from 'primevue/password';
import Button from 'primevue/button';

const authStore = useAuthStore();
const toast = useToast();
const fileInput = ref(null);
const loading = ref(false);
const passwordLoading = ref(false);

const user = reactive({
  name: 'John Doe',
  email: 'john.doe@example.com',
  role: 'Doctor',
  phone: '+1 234 567 890',
  address: '123 Medical Center St.',
});

const userAvatar = ref('/default-avatar.png');

const form = reactive({
  firstName: user.name.split(' ')[0],
  lastName: user.name.split(' ')[1],
  email: user.email,
  phone: user.phone,
  address: user.address,
});

const passwordForm = reactive({
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
});

const rules = {
  form: {
    firstName: { required },
    lastName: { required },
    email: { required, email },
    phone: { required },
    address: { required },
  },
  passwordForm: {
    currentPassword: { required },
    newPassword: { required, minLength: minLength(8) },
    confirmPassword: { required, sameAs: sameAs(passwordForm.newPassword) },
  },
};

const v$ = useVuelidate(rules, { form, passwordForm });

const triggerFileUpload = () => {
  fileInput.value.click();
};

const handleAvatarChange = async (event) => {
  const file = event.target.files[0];
  if (file) {
    try {
      // Handle file upload logic here
      const formData = new FormData();
      formData.append('avatar', file);

      // Example API call
      // await authStore.updateAvatar(formData);

      // Update avatar preview
      userAvatar.value = URL.createObjectURL(file);

      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Profile picture updated successfully',
        life: 3000,
      });
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Failed to update profile picture',
        life: 3000,
      });
    }
  }
};

const handleSubmit = async () => {
  const isValid = await v$.value.form.$validate();
  if (!isValid) return;

  try {
    loading.value = true;
    // Handle profile update logic here
    await authStore.updateProfile({
      name: `${form.firstName} ${form.lastName}`,
      email: form.email,
      phone: form.phone,
      address: form.address,
    });

    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Profile updated successfully',
      life: 3000,
    });
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to update profile',
      life: 3000,
    });
  } finally {
    loading.value = false;
  }
};

const handlePasswordChange = async () => {
  const isValid = await v$.value.passwordForm.$validate();
  if (!isValid) return;

  try {
    passwordLoading.value = true;
    // Handle password change logic here
    // await authStore.changePassword(passwordForm);

    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Password changed successfully',
      life: 3000,
    });

    // Reset form
    passwordForm.currentPassword = '';
    passwordForm.newPassword = '';
    passwordForm.confirmPassword = '';
    v$.value.passwordForm.$reset();
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to change password',
      life: 3000,
    });
  } finally {
    passwordLoading.value = false;
  }
};
</script>

<style scoped>
.profile {
  padding: 1rem;
}

.profile-header {
  text-align: center;
  margin-bottom: 2rem;
}

.avatar-wrapper {
  position: relative;
  width: 128px;
  height: 128px;
  margin: 0 auto 1rem;
}

.avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}

.avatar-upload {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 36px;
  height: 36px;
  border-radius: 50%;
}

.profile-info {
  margin-top: 2rem;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.info-item i {
  color: var(--primary-color);
}

.hidden {
  display: none;
}

.field {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.mt-4 {
  margin-top: 1.5rem;
}
</style>
