<template>
    <div class="dashboard">
        <h2 class="page-title">Dashboard</h2>

        <!-- Overview Cards -->
        <div class="overview-cards">
            <div class="card" v-for="(card, index) in overviewCards" :key="index" :class="card.class">
                <div class="card-icon">
                    <i :class="card.icon"></i>
                </div>
                <div class="card-content">
                    <h3>{{ card.value }}</h3>
                    <p>{{ card.label }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <button class="btn btn-primary" @click="$router.push('/patient/appointments/create')">
                    <i class="fas fa-calendar-plus"></i>
                    Book Appointment
                </button>
                <button class="btn btn-info" @click="$router.push('/patient/prescriptions')">
                    <i class="fas fa-prescription"></i>
                    View Prescriptions
                </button>
                <button class="btn btn-success" @click="$router.push('/patient/medical-records')">
                    <i class="fas fa-file-medical"></i>
                    Medical Records
                </button>
                <button class="btn btn-warning" @click="$router.push('/patient/billing')">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Check Bills
                </button>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="recent-appointments">
            <div class="section-header">
                <h3>Recent Appointments</h3>
                <router-link to="/patient/appointments" class="view-all">View All</router-link>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="appointment in recentAppointments" :key="appointment.id">
                            <td>{{ formatDate(appointment.date) }}</td>
                            <td>{{ appointment.doctor_name }}</td>
                            <td>{{ appointment.department }}</td>
                            <td>
                                <span :class="['status-badge', appointment.status.toLowerCase()]">
                                    {{ appointment.status }}
                                </span>
                            </td>
                            <td>
                                <router-link :to="'/patient/appointments/' + appointment.id" class="btn btn-sm btn-info">
                                    View Details
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Medical Records -->
        <div class="recent-records">
            <div class="section-header">
                <h3>Recent Medical Records</h3>
                <router-link to="/patient/medical-records" class="view-all">View All</router-link>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in recentRecords" :key="record.id">
                            <td>{{ formatDate(record.date) }}</td>
                            <td>{{ record.doctor_name }}</td>
                            <td>{{ record.type }}</td>
                            <td>
                                <router-link :to="'/patient/medical-records/' + record.id" class="btn btn-sm btn-info">
                                    View Details
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    name: 'PatientDashboard',
    setup() {
        const overviewCards = ref([
            { label: 'Upcoming Appointments', value: 0, icon: 'fas fa-calendar-check', class: 'primary' },
            { label: 'Recent Prescriptions', value: 0, icon: 'fas fa-prescription', class: 'success' },
            { label: 'Medical Records', value: 0, icon: 'fas fa-file-medical', class: 'info' },
            { label: 'Pending Bills', value: 0, icon: 'fas fa-file-invoice-dollar', class: 'warning' }
        ]);

        const recentAppointments = ref([]);
        const recentRecords = ref([]);

        const fetchDashboardData = async () => {
            try {
                const response = await axios.get('/api/patient/dashboard');
                const data = response.data;

                // Update overview cards
                overviewCards.value[0].value = data.upcomingAppointments;
                overviewCards.value[1].value = data.recentPrescriptions;
                overviewCards.value[2].value = data.medicalRecords;
                overviewCards.value[3].value = data.pendingBills;

                // Update recent appointments and records
                recentAppointments.value = data.recentAppointments;
                recentRecords.value = data.recentRecords;
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
            }
        };

        const formatDate = (date) => {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        };

        onMounted(() => {
            fetchDashboardData();
        });

        return {
            overviewCards,
            recentAppointments,
            recentRecords,
            formatDate
        };
    }
};
</script>

<style scoped>
.dashboard {
    padding: 1rem;
}

.page-title {
    margin-bottom: 2rem;
    color: #2c3e50;
}

.overview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-radius: 10px;
    color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card.primary { background: #3498db; }
.card.success { background: #2ecc71; }
.card.info { background: #00bcd4; }
.card.warning { background: #f1c40f; }

.card-icon {
    font-size: 2rem;
    margin-right: 1rem;
}

.card-content h3 {
    margin: 0;
    font-size: 1.5rem;
}

.card-content p {
    margin: 0;
    opacity: 0.9;
}

.quick-actions {
    margin-bottom: 2rem;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary { background: #3498db; color: white; }
.btn-info { background: #00bcd4; color: white; }
.btn-success { background: #2ecc71; color: white; }
.btn-warning { background: #f1c40f; color: white; }

.btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.view-all {
    color: #3498db;
    text-decoration: none;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th,
.table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.875rem;
}

.status-badge.scheduled { background: #3498db; color: white; }
.status-badge.completed { background: #2ecc71; color: white; }
.status-badge.cancelled { background: #e74c3c; color: white; }

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .overview-cards {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        grid-template-columns: 1fr;
    }

    .table-responsive {
        overflow-x: auto;
    }
}
</style>
