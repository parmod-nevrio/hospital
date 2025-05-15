<template>
  <div class="dashboard">
    <div class="grid">
      <!-- Statistics Cards -->
      <div class="col-12 md:col-6 lg:col-3">
        <div class="stats-card">
          <div class="stats-icon patients">
            <i class="pi pi-users"></i>
          </div>
          <div class="stats-info">
            <h3>Total Patients</h3>
            <h2>1,234</h2>
            <span class="trend positive">
              <i class="pi pi-arrow-up"></i> 12.5%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 md:col-6 lg:col-3">
        <div class="stats-card">
          <div class="stats-icon appointments">
            <i class="pi pi-calendar"></i>
          </div>
          <div class="stats-info">
            <h3>Appointments</h3>
            <h2>42</h2>
            <span class="trend">Today</span>
          </div>
        </div>
      </div>

      <div class="col-12 md:col-6 lg:col-3">
        <div class="stats-card">
          <div class="stats-icon doctors">
            <i class="pi pi-user-plus"></i>
          </div>
          <div class="stats-info">
            <h3>Doctors</h3>
            <h2>48</h2>
            <span class="trend">Active</span>
          </div>
        </div>
      </div>

      <div class="col-12 md:col-6 lg:col-3">
        <div class="stats-card">
          <div class="stats-icon revenue">
            <i class="pi pi-dollar"></i>
          </div>
          <div class="stats-info">
            <h3>Revenue</h3>
            <h2>$52.5k</h2>
            <span class="trend positive">
              <i class="pi pi-arrow-up"></i> 8.2%
            </span>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="col-12 lg:col-6">
        <Card>
          <template #title>Patient Statistics</template>
          <template #content>
            <Chart type="line" :data="patientStats" :options="chartOptions" />
          </template>
        </Card>
      </div>

      <div class="col-12 lg:col-6">
        <Card>
          <template #title>Department Distribution</template>
          <template #content>
            <Chart type="doughnut" :data="departmentStats" :options="chartOptions" />
          </template>
        </Card>
      </div>

      <!-- Recent Appointments -->
      <div class="col-12">
        <Card>
          <template #title>Recent Appointments</template>
          <template #content>
            <DataTable :value="recentAppointments" :rows="5" :paginator="true">
              <Column field="patient" header="Patient"></Column>
              <Column field="doctor" header="Doctor"></Column>
              <Column field="department" header="Department"></Column>
              <Column field="date" header="Date"></Column>
              <Column field="status" header="Status">
                <template #body="slotProps">
                  <Tag :value="slotProps.data.status" :severity="getStatusSeverity(slotProps.data.status)" />
                </template>
              </Column>
            </DataTable>
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from 'primevue/card';
import Chart from 'primevue/chart';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';

// Sample data for charts
const patientStats = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [
    {
      label: 'New Patients',
      data: [65, 59, 80, 81, 56, 55],
      fill: false,
      borderColor: '#4CAF50',
      tension: 0.4
    },
    {
      label: 'Follow-ups',
      data: [28, 48, 40, 19, 86, 27],
      fill: false,
      borderColor: '#2196F3',
      tension: 0.4
    }
  ]
};

const departmentStats = {
  labels: ['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Others'],
  datasets: [
    {
      data: [300, 250, 200, 150, 100],
      backgroundColor: [
        '#FF6384',
        '#36A2EB',
        '#FFCE56',
        '#4BC0C0',
        '#9966FF'
      ]
    }
  ]
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
};

// Sample data for recent appointments
const recentAppointments = [
  {
    patient: 'John Doe',
    doctor: 'Dr. Smith',
    department: 'Cardiology',
    date: '2024-02-20',
    status: 'scheduled'
  },
  {
    patient: 'Jane Smith',
    doctor: 'Dr. Johnson',
    department: 'Neurology',
    date: '2024-02-20',
    status: 'completed'
  },
  {
    patient: 'Robert Brown',
    doctor: 'Dr. Davis',
    department: 'Pediatrics',
    date: '2024-02-21',
    status: 'cancelled'
  }
];

const getStatusSeverity = (status) => {
  const severities = {
    scheduled: 'info',
    completed: 'success',
    cancelled: 'danger',
    pending: 'warning'
  };
  return severities[status] || 'info';
};
</script>

<style scoped>
.dashboard {
  padding: 1rem;
}

.stats-card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stats-icon {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stats-icon i {
  font-size: 1.5rem;
  color: white;
}

.patients {
  background-color: #4CAF50;
}

.appointments {
  background-color: #2196F3;
}

.doctors {
  background-color: #9C27B0;
}

.revenue {
  background-color: #FF9800;
}

.stats-info h3 {
  margin: 0;
  font-size: 0.875rem;
  color: #64748b;
}

.stats-info h2 {
  margin: 0.5rem 0;
  font-size: 1.5rem;
  color: #1e293b;
}

.trend {
  font-size: 0.875rem;
  color: #64748b;
}

.trend.positive {
  color: #4CAF50;
}

.trend.negative {
  color: #f44336;
}

:deep(.p-card) {
  margin-bottom: 1rem;
}

:deep(.p-datatable) {
  margin-top: 1rem;
}
</style>
