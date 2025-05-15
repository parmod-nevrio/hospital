<template>
  <div class="roles-page">
    <div class="page-header">
      <h1>Role Management</h1>
      <Button
        label="Add Role"
        icon="pi pi-plus"
        @click="openRoleDialog"
      />
    </div>

    <Card>
      <template #content>
        <DataTable
          :value="roles"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          filterDisplay="menu"
          :globalFilterFields="['name', 'description']"
          responsiveLayout="scroll"
        >
          <template #header>
            <div class="table-header">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText
                  v-model="filters['global'].value"
                  placeholder="Search roles..."
                />
              </span>
            </div>
          </template>

          <Column field="name" header="Role Name" sortable>
            <template #body="slotProps">
              <div class="role-info">
                <i :class="getRoleIcon(slotProps.data.name)" class="role-icon" />
                <span>{{ slotProps.data.name }}</span>
              </div>
            </template>
          </Column>
          <Column field="description" header="Description" sortable />
          <Column field="users" header="Users" sortable>
            <template #body="slotProps">
              <Tag :value="slotProps.data.users + ' users'" />
            </template>
          </Column>
          <Column field="permissions" header="Permissions">
            <template #body="slotProps">
              <div class="permissions-list">
                <Chip
                  v-for="permission in slotProps.data.permissions"
                  :key="permission"
                  :label="permission"
                  class="permission-chip"
                />
              </div>
            </template>
          </Column>
          <Column header="Actions" :exportable="false" style="min-width: 8rem">
            <template #body="slotProps">
              <Button
                icon="pi pi-pencil"
                class="p-button-rounded p-button-text"
                @click="editRole(slotProps.data)"
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-text p-button-danger"
                @click="confirmDeleteRole(slotProps.data)"
              />
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <!-- Role Dialog -->
    <Dialog
      v-model:visible="roleDialog"
      :style="{ width: '600px' }"
      header="Role Details"
      :modal="true"
      class="p-fluid"
    >
      <div class="field">
        <label for="name">Role Name</label>
        <InputText
          id="name"
          v-model.trim="role.name"
          required
          autofocus
          :class="{ 'p-invalid': submitted && !role.name }"
        />
        <small class="p-error" v-if="submitted && !role.name">Role name is required.</small>
      </div>
      <div class="field">
        <label for="description">Description</label>
        <Textarea
          id="description"
          v-model="role.description"
          rows="3"
          :class="{ 'p-invalid': submitted && !role.description }"
        />
        <small class="p-error" v-if="submitted && !role.description">Description is required.</small>
      </div>
      <div class="field">
        <label>Permissions</label>
        <div class="permissions-grid">
          <div v-for="category in permissionCategories" :key="category.name" class="permission-category">
            <h3>{{ category.name }}</h3>
            <div class="permission-options">
              <div v-for="permission in category.permissions" :key="permission.value" class="permission-option">
                <Checkbox
                  :id="permission.value"
                  v-model="role.permissions"
                  :value="permission.value"
                  :binary="false"
                />
                <label :for="permission.value">{{ permission.label }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <template #footer>
        <Button
          label="Cancel"
          icon="pi pi-times"
          class="p-button-text"
          @click="hideDialog"
        />
        <Button
          label="Save"
          icon="pi pi-check"
          class="p-button-text"
          @click="saveRole"
        />
      </template>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog
      v-model:visible="deleteRoleDialog"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="role">Are you sure you want to delete <b>{{ role.name }}</b>?</span>
      </div>
      <template #footer>
        <Button
          label="No"
          icon="pi pi-times"
          class="p-button-text"
          @click="deleteRoleDialog = false"
        />
        <Button
          label="Yes"
          icon="pi pi-check"
          class="p-button-text p-button-danger"
          @click="deleteRole"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { roleService } from '@/services/api';
import Card from 'primevue/card';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Checkbox from 'primevue/checkbox';
import Tag from 'primevue/tag';
import Chip from 'primevue/chip';

const toast = useToast();
const roles = ref([]);
const roleDialog = ref(false);
const deleteRoleDialog = ref(false);
const role = ref({});
const submitted = ref(false);
const loading = ref(false);

const filters = ref({
  global: { value: null }
});

const permissionCategories = [
  {
    name: 'User Management',
    permissions: [
      { label: 'View Users', value: 'users.view' },
      { label: 'Create Users', value: 'users.create' },
      { label: 'Edit Users', value: 'users.edit' },
      { label: 'Delete Users', value: 'users.delete' }
    ]
  },
  {
    name: 'Role Management',
    permissions: [
      { label: 'View Roles', value: 'roles.view' },
      { label: 'Create Roles', value: 'roles.create' },
      { label: 'Edit Roles', value: 'roles.edit' },
      { label: 'Delete Roles', value: 'roles.delete' }
    ]
  },
  {
    name: 'Patient Management',
    permissions: [
      { label: 'View Patients', value: 'patients.view' },
      { label: 'Create Patients', value: 'patients.create' },
      { label: 'Edit Patients', value: 'patients.edit' },
      { label: 'Delete Patients', value: 'patients.delete' }
    ]
  },
  {
    name: 'Appointment Management',
    permissions: [
      { label: 'View Appointments', value: 'appointments.view' },
      { label: 'Create Appointments', value: 'appointments.create' },
      { label: 'Edit Appointments', value: 'appointments.edit' },
      { label: 'Delete Appointments', value: 'appointments.delete' }
    ]
  }
];

const getRoleIcon = (roleName) => {
  const icons = {
    admin: 'pi pi-shield',
    doctor: 'pi pi-user-plus',
    nurse: 'pi pi-heart',
    staff: 'pi pi-users'
  };
  return icons[roleName.toLowerCase()] || 'pi pi-user';
};

const openRoleDialog = () => {
  role.value = {
    name: '',
    description: '',
    permissions: []
  };
  submitted.value = false;
  roleDialog.value = true;
};

const hideDialog = () => {
  roleDialog.value = false;
  submitted.value = false;
};

const editRole = (data) => {
  role.value = { ...data };
  roleDialog.value = true;
};

const confirmDeleteRole = (data) => {
  role.value = data;
  deleteRoleDialog.value = true;
};

const saveRole = async () => {
  submitted.value = true;

  if (role.value.name && role.value.description) {
    try {
      if (role.value.id) {
        // Update existing role
        const response = await roleService.update(role.value.id, role.value);
        const index = roles.value.findIndex(r => r.id === role.value.id);
        roles.value[index] = response.data;
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Role Updated', life: 3000 });
      } else {
        // Create new role
        const response = await roleService.create(role.value);
        roles.value.push(response.data);
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Role Created', life: 3000 });
      }

      roleDialog.value = false;
      role.value = {};
    } catch (error) {
      toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'An error occurred', life: 3000 });
    }
  }
};

const deleteRole = async () => {
  try {
    await roleService.delete(role.value.id);
    roles.value = roles.value.filter(r => r.id !== role.value.id);
    deleteRoleDialog.value = false;
    role.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Role Deleted', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'An error occurred', life: 3000 });
  }
};

onMounted(async () => {
  try {
    loading.value = true;
    const response = await roleService.getAll();
    roles.value = response.data;
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'Failed to load roles', life: 3000 });
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.roles-page {
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

.role-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.role-icon {
  font-size: 1.25rem;
  color: #64748b;
}

.permissions-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.permission-chip {
  background-color: #f1f5f9;
  color: #1e293b;
}

.permissions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-top: 0.5rem;
}

.permission-category {
  background-color: #f8f9fa;
  border-radius: 6px;
  padding: 1rem;
}

.permission-category h3 {
  margin: 0 0 0.5rem 0;
  font-size: 1rem;
  color: #1e293b;
}

.permission-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.permission-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
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
