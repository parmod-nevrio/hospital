<template>
    <div class="prescriptions">
        <div class="page-header">
            <h2 class="page-title">Prescriptions</h2>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <input type="text" v-model="searchQuery" placeholder="Search prescriptions...">
                <i class="fas fa-search"></i>
            </div>
            <div class="filter-group">
                <select v-model="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div class="filter-group">
                <select v-model="dateFilter">
                    <option value="">All Time</option>
                    <option value="last_month">Last Month</option>
                    <option value="last_3_months">Last 3 Months</option>
                    <option value="last_year">Last Year</option>
                </select>
            </div>
        </div>

        <!-- Prescriptions List -->
        <div class="prescriptions-list">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="prescription in filteredPrescriptions" :key="prescription.id">
                            <td>{{ formatDate(prescription.date) }}</td>
                            <td>{{ prescription.doctor_name }}</td>
                            <td>{{ prescription.department }}</td>
                            <td>
                                <span :class="['status-badge', prescription.status.toLowerCase()]">
                                    {{ prescription.status }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-sm btn-info" @click="viewPrescription(prescription)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" @click="downloadPrescription(prescription)">
                                        <i class="fas fa-download"></i>
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

        <!-- Prescription Details Modal -->
        <div class="modal" v-if="selectedPrescription">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Prescription Details</h3>
                    <button class="close-btn" @click="selectedPrescription = null">&times;</button>
                </div>
                <div class="prescription-details">
                    <div class="detail-group">
                        <label>Date</label>
                        <p>{{ formatDate(selectedPrescription.date) }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Doctor</label>
                        <p>{{ selectedPrescription.doctor_name }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Department</label>
                        <p>{{ selectedPrescription.department }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Status</label>
                        <p>
                            <span :class="['status-badge', selectedPrescription.status.toLowerCase()]">
                                {{ selectedPrescription.status }}
                            </span>
                        </p>
                    </div>
                    <div class="detail-group">
                        <label>Medications</label>
                        <div class="medications-list">
                            <div v-for="med in selectedPrescription.medications" :key="med.id" class="medication-item">
                                <div class="medication-header">
                                    <h4>{{ med.name }}</h4>
                                    <span class="dosage">{{ med.dosage }}</span>
                                </div>
                                <div class="medication-details">
                                    <p><strong>Frequency:</strong> {{ med.frequency }}</p>
                                    <p><strong>Duration:</strong> {{ med.duration }}</p>
                                    <p v-if="med.instructions"><strong>Instructions:</strong> {{ med.instructions }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="detail-group" v-if="selectedPrescription.notes">
                        <label>Additional Notes</label>
                        <p>{{ selectedPrescription.notes }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" @click="downloadPrescription(selectedPrescription)">
                        <i class="fas fa-download"></i>
                        Download Prescription
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
    name: 'Prescriptions',
    setup() {
        const prescriptions = ref([]);
        const selectedPrescription = ref(null);
        const searchQuery = ref('');
        const statusFilter = ref('');
        const dateFilter = ref('');
        const currentPage = ref(1);
        const totalPages = ref(1);

        const fetchPrescriptions = async () => {
            try {
                const response = await axios.get('/api/patient/prescriptions', {
                    params: {
                        page: currentPage.value,
                        search: searchQuery.value,
                        status: statusFilter.value,
                        date: dateFilter.value
                    }
                });
                prescriptions.value = response.data.data;
                totalPages.value = response.data.last_page;
            } catch (error) {
                console.error('Error fetching prescriptions:', error);
            }
        };

        const viewPrescription = async (prescription) => {
            try {
                const response = await axios.get(`/api/patient/prescriptions/${prescription.id}`);
                selectedPrescription.value = response.data;
            } catch (error) {
                console.error('Error fetching prescription details:', error);
            }
        };

        const downloadPrescription = async (prescription) => {
            try {
                const response = await axios.get(`/api/patient/prescriptions/${prescription.id}/download`, {
                    responseType: 'blob'
                });
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `prescription-${prescription.id}.pdf`);
                document.body.appendChild(link);
                link.click();
                link.remove();
            } catch (error) {
                console.error('Error downloading prescription:', error);
            }
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
            fetchPrescriptions();
        };

        const filteredPrescriptions = computed(() => {
            return prescriptions.value;
        });

        onMounted(() => {
            fetchPrescriptions();
        });

        return {
            prescriptions,
            selectedPrescription,
            searchQuery,
            statusFilter,
            dateFilter,
            currentPage,
            totalPages,
            filteredPrescriptions,
            viewPrescription,
            downloadPrescription,
            formatDate,
            changePage
        };
    }
};
</script>

<style scoped>
.prescriptions {
    padding: 1rem;
}

.page-header {
    margin-bottom: 2rem;
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

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.875rem;
}

.status-badge.active { background: #2ecc71; color: white; }
.status-badge.completed { background: #3498db; color: white; }
.status-badge.expired { background: #e74c3c; color: white; }

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
    max-width: 600px;
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

.prescription-details {
    margin-bottom: 1.5rem;
}

.detail-group {
    margin-bottom: 1rem;
}

.detail-group label {
    display: block;
    color: #666;
    margin-bottom: 0.25rem;
}

.detail-group p {
    margin: 0;
    color: #2c3e50;
}

.medications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.medication-item {
    background: #f8f9fa;
    border-radius: 5px;
    padding: 1rem;
}

.medication-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.medication-header h4 {
    margin: 0;
    color: #2c3e50;
}

.dosage {
    background: #3498db;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.875rem;
}

.medication-details p {
    margin: 0.25rem 0;
    color: #666;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    padding-top: 1rem;
    border-top: 1px solid #eee;
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
