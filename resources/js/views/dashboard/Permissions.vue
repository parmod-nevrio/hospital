<template>
  <div class="permissions-page">
    <div class="page-header">
      <h1>Permission Management</h1>
    </div>

    <div class="grid">
      <div v-for="category in permissionCategories" :key="category.name" class="col-12 md:col-6 lg:col-4">
        <Card>
          <template #title>
            <div class="card-title">
              <i :class="getCategoryIcon(category.name)" class="category-icon" />
              {{ category.name }}
            </div>
          </template>
          <template #content>
            <div class="permissions-list">
              <div
                v-for="permission in category.permissions"
                :key="permission.value"
                class="permission-item"
                @click="openPermissionDetails(permission)"
              >
                <div class="permission-info">
                  <span class="permission-name">{{ permission.label }}</span>
                  <span class="permission-value">{{ permission.value }}</span>
                </div>
                <div class="permission-stats">
                  <Tag :value="getRoleCount(permission.value) + ' roles'" severity="info" />
                </div>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>

    <!-- Permission Details Dialog -->
    <Dialog
      v-model:visible="permissionDialog"
      :style="{ width: '600px' }"
      header="Permission Details"
      :modal="true"
    >
      <div v-if="selectedPermission" class="permission-details">
        <div class="field">
          <label>Permission Name</label>
          <div class="permission-value">{{ selectedPermission.label }}</div>
        </div>
        <div class="field">
          <label>Permission Key</label>
          <div class="permission-value">{{ selectedPermission.value }}</div>
        </div>
        <div class="field">
          <label>Roles with this Permission</label>
          <div class="roles-list">
            <div v-for="role in roles" :key="role.id" class="role-item">
              <Checkbox
                :id="'role-' + role.id"
                v-model="role.permissions"
                :value="selectedPermission.value"
                :binary="false"
                @change="(e) => updateRolePermission(role.id, selectedPermission.value, e.checked)"
              />
              <label :for="'role-' + role.id">{{ role.name }}</label>
            </div>
          </div>
        </div>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { permissionService, roleService } from '@/services/api';
import Card from 'primevue/card';
import Dialog from 'primevue/dialog';
import Tag from 'primevue/tag';
import Chip from 'primevue/chip';
import Checkbox from 'primevue/checkbox';

const toast = useToast();
const permissionDialog = ref(false);
const selectedPermission = ref(null);
const roles = ref([]);
const loading = ref(false);

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
  },
  {
    name: 'Department Management',
    permissions: [
      { label: 'View Departments', value: 'departments.view' },
      { label: 'Create Departments', value: 'departments.create' },
      { label: 'Edit Departments', value: 'departments.edit' },
      { label: 'Delete Departments', value: 'departments.delete' }
    ]
  },
  {
    name: 'Settings',
    permissions: [
      { label: 'View Settings', value: 'settings.view' },
      { label: 'Edit Settings', value: 'settings.edit' }
    ]
  }
];

const getCategoryIcon = (categoryName) => {
  const icons = {
    'User Management': 'pi pi-users',
    'Role Management': 'pi pi-shield',
    'Patient Management': 'pi pi-heart',
    'Appointment Management': 'pi pi-calendar',
    'Department Management': 'pi pi-building',
    'Settings': 'pi pi-cog'
  };
  return icons[categoryName] || 'pi pi-list';
};

const openPermissionDetails = (permission) => {
  selectedPermission.value = permission;
  permissionDialog.value = true;
};

const getRoleCount = (permissionValue) => {
  return roles.value.filter(role => role.permissions.includes(permissionValue)).length;
};

const getRolesWithPermission = (permissionValue) => {
  return roles.value.filter(role => role.permissions.includes(permissionValue));
};

const updateRolePermission = async (roleId, permissionValue, hasPermission) => {
  try {
    const role = roles.value.find(r => r.id === roleId);
    if (role) {
      if (hasPermission) {
        if (!role.permissions.includes(permissionValue)) {
          role.permissions.push(permissionValue);
        }
      } else {
        role.permissions = role.permissions.filter(p => p !== permissionValue);
      }

      await permissionService.updateRoles(selectedPermission.value.id, roles.value.map(r => r.id));
      toast.add({ severity: 'success', summary: 'Successful', detail: 'Permission Updated', life: 3000 });
    }
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'An error occurred', life: 3000 });
  }
};

onMounted(async () => {
  try {
    loading.value = true;
    const [permissionsResponse, rolesResponse] = await Promise.all([
      permissionService.getAll(),
      roleService.getAll()
    ]);
    roles.value = rolesResponse.data;
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'Failed to load data', life: 3000 });
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.permissions-page {
  padding: 1rem;
}

.page-header {
  margin-bottom: 1.5rem;
}

.page-header h1 {
  margin: 0;
  font-size: 1.5rem;
  color: #1e293b;
}

.card-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #1e293b;
  font-weight: 600;
}

.category-icon {
  font-size: 1.25rem;
  color: #64748b;
}

.permissions-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.permission-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background-color: #f8f9fa;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.permission-item:hover {
  background-color: #f1f5f9;
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.permission-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.permission-name {
  font-weight: 500;
  color: #1e293b;
}

.permission-value {
  font-size: 0.875rem;
  color: #64748b;
}

.permission-stats {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.permission-details {
  padding: 1rem;
}

.field {
  margin-bottom: 1.5rem;
}

.field label {
  display: block;
  margin-bottom: 0.5rem;
  color: #64748b;
  font-weight: 500;
}

.roles-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  max-height: 300px;
  overflow-y: auto;
  padding: 0.5rem;
  background-color: #f8f9fa;
  border-radius: 6px;
}

.role-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
  background-color: white;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.role-item:hover {
  background-color: #f1f5f9;
}

.role-item label {
  margin: 0;
  cursor: pointer;
  user-select: none;
}

:deep(.p-card) {
  height: 100%;
}

:deep(.p-card .p-card-content) {
  padding: 0;
}

:deep(.p-tag) {
  background-color: #e2e8f0;
  color: #1e293b;
}

:deep(.p-checkbox) {
  margin-right: 0.5rem;
}

:deep(.p-dialog .p-dialog-content) {
  padding: 0;
}
</style>
