<template>
  <div class="users-page">
    <div class="page-header">
      <h1>User Management</h1>
      <Button
        label="Add User"
        icon="pi pi-plus"
        @click="openUserDialog"
      />
    </div>

    <Card>
      <template #title>
        <div class="flex justify-content-between align-items-center">
          <h2 class="m-0">Users</h2>
          <Button label="New User" icon="pi pi-plus" @click="openUserDialog" />
        </div>
      </template>
      <template #content>
        <DataTable
          v-model:filters="filters"
          :value="users"
          :loading="loading"
          paginator
          :rows="10"
          filterDisplay="menu"
          :globalFilterFields="['name', 'email', 'role.name', 'status']"
          responsiveLayout="scroll"
        >
          <template #empty>
            <div class="text-center p-4">
              <i class="pi pi-users text-4xl text-500 mb-3"></i>
              <p class="text-500">No users found.</p>
            </div>
          </template>
          <template #loading>
            <div class="text-center p-4">
              <i class="pi pi-spin pi-spinner text-4xl text-500"></i>
              <p class="text-500">Loading users...</p>
            </div>
          </template>

          <Column field="name" header="Name" sortable style="min-width: 14rem">
            <template #body="{ data }">
              <div class="flex align-items-center gap-2">
                <Avatar :label="data.name.charAt(0)" class="bg-primary" />
                <span>{{ data.name }}</span>
              </div>
            </template>
          </Column>
          <Column field="email" header="Email" sortable style="min-width: 14rem" />
          <Column field="role.name" header="Role" sortable style="min-width: 10rem">
            <template #body="{ data }">
              <Tag :value="data.role?.name" :severity="getRoleSeverity(data.role?.name)" />
            </template>
          </Column>
          <Column field="status" header="Status" sortable style="min-width: 10rem">
            <template #body="{ data }">
              <Tag :value="data.status" :severity="getStatusSeverity(data.status)" />
            </template>
          </Column>
          <Column style="min-width: 8rem">
            <template #body="{ data }">
              <Button icon="pi pi-pencil" text rounded @click="editUser(data)" />
              <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeleteUser(data)" />
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <!-- User Dialog -->
    <Dialog
      v-model:visible="userDialog"
      :style="{ width: '450px' }"
      header="User Details"
      :modal="true"
      class="p-fluid"
    >
      <div class="field">
        <label for="name">Name</label>
        <InputText
          id="name"
          v-model.trim="user.name"
          required
          autofocus
          :class="{ 'p-invalid': submitted && !user.name }"
        />
        <small class="p-error" v-if="submitted && !user.name">Name is required.</small>
      </div>
      <div class="field">
        <label for="email">Email</label>
        <InputText
          id="email"
          v-model.trim="user.email"
          required
          :class="{ 'p-invalid': submitted && !user.email }"
        />
        <small class="p-error" v-if="submitted && !user.email">Email is required.</small>
      </div>
      <div class="field" v-if="!user.id">
        <label for="password">Password</label>
        <Password
          id="password"
          v-model="user.password"
          :feedback="false"
          toggleMask
          required
          :class="{ 'p-invalid': submitted && !user.password }"
        />
        <small class="p-error" v-if="submitted && !user.password">Password is required.</small>
      </div>
      <div class="field">
        <label for="role">Role</label>
        <Dropdown
          id="role"
          v-model="user.role_id"
          :options="roles"
          optionLabel="name"
          optionValue="value"
          placeholder="Select a Role"
          :class="{ 'p-invalid': submitted && !user.role_id }"
        />
        <small class="p-error" v-if="submitted && !user.role_id">Role is required.</small>
      </div>
      <div class="field">
        <label for="status">Status</label>
        <Dropdown
          id="status"
          v-model="user.status"
          :options="statuses"
          optionLabel="name"
          optionValue="value"
          placeholder="Select a Status"
          :class="{ 'p-invalid': submitted && !user.status }"
        />
        <small class="p-error" v-if="submitted && !user.status">Status is required.</small>
      </div>


      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          text
          @click="hideDialog"
        />
        <Button
          label="Save"
          icon="pi pi-check"
          text
          @click="saveUser"
        />
      </template>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog
      v-model:visible="deleteUserDialog"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="user">Are you sure you want to delete <b>{{ user.name }}</b>?</span>
      </div>
      <template #footer>
        <Button
          label="No"
          icon="pi pi-times"
          text
          @click="deleteUserDialog = false"
        />
        <Button
          label="Yes"
          icon="pi pi-check"
          text
          @click="deleteUser"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { userService, roleService } from '@/services/api';
import Card from 'primevue/card';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import Avatar from 'primevue/avatar';
import Password from 'primevue/password';

const toast = useToast();
const users = ref([]);
const roles = ref([]);
const userDialog = ref(false);
const deleteUserDialog = ref(false);
const user = ref({});
const submitted = ref(false);
const loading = ref(false);

const filters = ref({
  global: { value: null }
});

const statuses = [
  { name: 'Active', value: 'active' },
  { name: 'Inactive', value: 'inactive' },
  { name: 'Pending', value: 'pending' }
];

const getRoleSeverity = (role) => {
  const severities = {
    admin: 'danger',
    doctor: 'success',
    nurse: 'info',
    staff: 'warning'
  };
  return severities[role] || 'info';
};

const getStatusSeverity = (status) => {
  const severities = {
    active: 'success',
    inactive: 'danger',
    pending: 'warning'
  };
  return severities[status] || 'info';
};

const openUserDialog = () => {
  user.value = {
    name: '',
    email: '',
    password: '',
    role_id: null,
    status: 'active'
  };
  submitted.value = false;
  userDialog.value = true;
};

const hideDialog = () => {
  userDialog.value = false;
  submitted.value = false;
};

const editUser = (data) => {
  user.value = { ...data };
  userDialog.value = true;
};

const confirmDeleteUser = (data) => {
  user.value = data;
  deleteUserDialog.value = true;
};

const saveUser = async () => {
  submitted.value = true;

  if (user.value.name && user.value.email && user.value.role_id && user.value.status && (!user.value.id || user.value.password)) {
    try {
      const userData = { ...user.value };
      if (user.value.id) {
        // Remove password if not changed
        if (!userData.password) {
          delete userData.password;
        }
        // Update existing user
        const response = await userService.update(user.value.id, userData);
        const index = users.value.findIndex(u => u.id === user.value.id);
        users.value[index] = response.data;
        toast.add({ severity: 'success', summary: 'Successful', detail: 'User Updated', life: 3000 });
      } else {
        // Create new user
        const response = await userService.create(userData);
        users.value.push(response.data);
        toast.add({ severity: 'success', summary: 'Successful', detail: 'User Created', life: 3000 });
      }

      userDialog.value = false;
      user.value = {};
    } catch (error) {
      toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'An error occurred', life: 3000 });
    }
  }
};

const deleteUser = async () => {
  try {
    await userService.delete(user.value.id);
    users.value = users.value.filter(u => u.id !== user.value.id);
    deleteUserDialog.value = false;
    user.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'User Deleted', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'An error occurred', life: 3000 });
  }
};

onMounted(async () => {
  try {
    loading.value = true;
    // Fetch users
    const usersResponse = await userService.getAll();
    users.value = usersResponse.data;

    // Fetch roles
    const rolesResponse = await roleService.getAll();
    roles.value = rolesResponse.data.map(role => ({
      name: role.name,
      value: role.id
    }));
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'Failed to load data', life: 3000 });
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.users-page {
  padding: 1rem;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.page-header h1 {
  margin: 0;
  font-size: 1.5rem;
  color: #1e293b;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
}

:deep(.p-datatable .p-datatable-header) {
  padding: 1rem;
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 6px;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
  background: #f8f9fa;
  color: #1e293b;
  font-weight: 600;
}

:deep(.p-datatable .p-datatable-tbody > tr) {
  background: #ffffff;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background: #f8f9fa;
}

:deep(.p-button.p-button-text.p-button-danger) {
  color: #ef4444;
}

:deep(.p-button.p-button-text.p-button-danger:hover) {
  background: rgba(239, 68, 68, 0.1);
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  padding: 1rem;
}
</style>
