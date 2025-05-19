<template>
    <div class="appointments">
        <div class="page-header">
            <h2 class="page-title">Appointments</h2>
            <button class="btn btn-primary" @click="showCreateForm = true">
                <i class="fas fa-plus"></i>
                Book New Appointment
            </button>
        </div>

        <!-- Appointment Creation Form -->
        <div class="modal" v-if="showCreateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Book New Appointment</h3>
                    <button class="close-btn" @click="showCreateForm = false">&times;</button>
                </div>
                <form @submit.prevent="createAppointment">
                    <div class="form-group">
                        <label>Department</label>
                        <select v-model="newAppointment.department_id" required>
                            <option value="">Select Department</option>
                            <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                {{ dept.name }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Doctor</label>
                        <select v-model="newAppointment.doctor_id" required>
                            <option value="">Select Doctor</option>
                            <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">
                                {{ doctor.name }} - {{ doctor.specialization }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" v-model="newAppointment.date" required>
                    </div>

                    <div class="form-group">
                        <label>Time</label>
                        <select v-model="newAppointment.time" required>
                            <option value="">Select Time</option>
                            <option v-for="slot in availableTimeSlots" :key="slot" :value="slot">
                                {{ slot }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Reason for Visit</label>
                        <textarea v-model="newAppointment.reason" rows="3" required></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" @click="showCreateForm = false">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                            {{ isSubmitting ? 'Booking...' : 'Book Appointment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="appointments-list">
            <div class="filters">
                <div class="search-box">
                    <input type="text" v-model="searchQuery" placeholder="Search appointments...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="filter-group">
                    <select v-model="statusFilter">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="appointment in filteredAppointments" :key="appointment.id">
                            <td>
                                <div class="datetime">
                                    <div>{{ formatDate(appointment.date) }}</div>
                                    <small>{{ appointment.time }}</small>
                                </div>
                            </td>
                            <td>{{ appointment.doctor_name }}</td>
                            <td>{{ appointment.department }}</td>
                            <td>
                                <span :class="['status-badge', appointment.status.toLowerCase()]">
                                    {{ appointment.status }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-sm btn-info" @click="viewAppointment(appointment)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button
                                        v-if="appointment.status === 'Scheduled'"
                                        class="btn btn-sm btn-danger"
                                        @click="cancelAppointment(appointment)"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination" v-if="totalPages > 1">
                <button
                    class="btn btn-sm"
                    :disabled="currentPage === 1"
                    @click="changePage(currentPage - 1)"
                >
                    Previous
                </button>
                <span>Page {{ currentPage }} of {{ totalPages }}</span>
                <button
                    class="btn btn-sm"
                    :disabled="currentPage === totalPages"
                    @click="changePage(currentPage + 1)"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
    name: 'Appointments',
    setup() {
        const appointments = ref([]);
        const departments = ref([]);
        const doctors = ref([]);
        const showCreateForm = ref(false);
        const isSubmitting = ref(false);
        const searchQuery = ref('');
        const statusFilter = ref('');
        const currentPage = ref(1);
        const totalPages = ref(1);

        const newAppointment = ref({
            department_id: '',
            doctor_id: '',
            date: '',
            time: '',
            reason: ''
        });

        const availableTimeSlots = [
            '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM',
            '11:30 AM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM',
            '04:00 PM', '04:30 PM'
        ];

        const fetchAppointments = async () => {
            try {
                const response = await axios.get('/api/patient/appointments', {
                    params: {
                        page: currentPage.value,
                        search: searchQuery.value,
                        status: statusFilter.value
                    }
                });
                appointments.value = response.data.data;
                totalPages.value = response.data.last_page;
            } catch (error) {
                console.error('Error fetching appointments:', error);
            }
        };

        const fetchDepartments = async () => {
            try {
                const response = await axios.get('/api/departments');
                departments.value = response.data;
            } catch (error) {
                console.error('Error fetching departments:', error);
            }
        };

        const fetchDoctors = async (departmentId) => {
            try {
                const response = await axios.get(`/api/departments/${departmentId}/doctors`);
                doctors.value = response.data;
            } catch (error) {
                console.error('Error fetching doctors:', error);
            }
        };

        const createAppointment = async () => {
            isSubmitting.value = true;
            try {
                await axios.post('/api/patient/appointments', newAppointment.value);
                showCreateForm.value = false;
                fetchAppointments();
                resetForm();
            } catch (error) {
                console.error('Error creating appointment:', error);
            } finally {
                isSubmitting.value = false;
            }
        };

        const cancelAppointment = async (appointment) => {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                try {
                    await axios.post(`/api/patient/appointments/${appointment.id}/cancel`);
                    fetchAppointments();
                } catch (error) {
                    console.error('Error cancelling appointment:', error);
                }
            }
        };

        const viewAppointment = (appointment) => {
            // Implement appointment details view
        };

        const resetForm = () => {
            newAppointment.value = {
                department_id: '',
                doctor_id: '',
                date: '',
                time: '',
                reason: ''
            };
        };

        const formatDate = (date) => {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        };

        const changePage = (page) => {
            currentPage.value = page;
            fetchAppointments();
        };

        const filteredAppointments = computed(() => {
            return appointments.value;
        });

        onMounted(() => {
            fetchAppointments();
            fetchDepartments();
        });

        return {
            appointments,
            departments,
            doctors,
            showCreateForm,
            isSubmitting,
            newAppointment,
            availableTimeSlots,
            searchQuery,
            statusFilter,
            currentPage,
            totalPages,
            filteredAppointments,
            createAppointment,
            cancelAppointment,
            viewAppointment,
            formatDate,
            changePage
        };
    }
};
</script>

<style scoped>
.appointments {
    padding: 1rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
}

.filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-box {
    position: relative;
    flex: 1;
}

.search-box input {
    width: 100%;
    padding: 0.5rem 2rem 0.5rem 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.search-box i {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.filter-group select {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.datetime {
    display: flex;
    flex-direction: column;
}

.datetime small {
    color: #666;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .filters {
        flex-direction: column;
    }

    .modal-content {
        margin: 1rem;
    }
}
</style>
