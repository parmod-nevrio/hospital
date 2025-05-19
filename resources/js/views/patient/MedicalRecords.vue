<template>
    <div class="medical-records">
        <div class="page-header">
            <h2 class="page-title">Medical Records</h2>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <input type="text" v-model="searchQuery" placeholder="Search records...">
                <i class="fas fa-search"></i>
            </div>
            <div class="filter-group">
                <select v-model="typeFilter">
                    <option value="">All Types</option>
                    <option value="consultation">Consultation</option>
                    <option value="lab_result">Lab Result</option>
                    <option value="prescription">Prescription</option>
                    <option value="procedure">Procedure</option>
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

        <!-- Records List -->
        <div class="records-list">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Doctor</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in filteredRecords" :key="record.id">
                            <td>{{ formatDate(record.date) }}</td>
                            <td>
                                <span :class="['type-badge', record.type.toLowerCase()]">
                                    {{ record.type }}
                                </span>
                            </td>
                            <td>{{ record.doctor_name }}</td>
                            <td>{{ record.department }}</td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-sm btn-info" @click="viewRecord(record)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" @click="downloadRecord(record)">
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

        <!-- Record Details Modal -->
        <div class="modal" v-if="selectedRecord">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Medical Record Details</h3>
                    <button class="close-btn" @click="selectedRecord = null">&times;</button>
                </div>
                <div class="record-details">
                    <div class="detail-group">
                        <label>Date</label>
                        <p>{{ formatDate(selectedRecord.date) }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Type</label>
                        <p>
                            <span :class="['type-badge', selectedRecord.type.toLowerCase()]">
                                {{ selectedRecord.type }}
                            </span>
                        </p>
                    </div>
                    <div class="detail-group">
                        <label>Doctor</label>
                        <p>{{ selectedRecord.doctor_name }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Department</label>
                        <p>{{ selectedRecord.department }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Diagnosis</label>
                        <p>{{ selectedRecord.diagnosis }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Treatment</label>
                        <p>{{ selectedRecord.treatment }}</p>
                    </div>
                    <div class="detail-group" v-if="selectedRecord.notes">
                        <label>Additional Notes</label>
                        <p>{{ selectedRecord.notes }}</p>
                    </div>
                    <div class="detail-group" v-if="selectedRecord.attachments?.length">
                        <label>Attachments</label>
                        <div class="attachments">
                            <a
                                v-for="attachment in selectedRecord.attachments"
                                :key="attachment.id"
                                :href="attachment.url"
                                target="_blank"
                                class="attachment-link"
                            >
                                <i class="fas fa-file"></i>
                                {{ attachment.name }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" @click="downloadRecord(selectedRecord)">
                        <i class="fas fa-download"></i>
                        Download Record
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
    name: 'MedicalRecords',
    setup() {
        const records = ref([]);
        const selectedRecord = ref(null);
        const searchQuery = ref('');
        const typeFilter = ref('');
        const dateFilter = ref('');
        const currentPage = ref(1);
        const totalPages = ref(1);

        const fetchRecords = async () => {
            try {
                const response = await axios.get('/api/patient/medical-records', {
                    params: {
                        page: currentPage.value,
                        search: searchQuery.value,
                        type: typeFilter.value,
                        date: dateFilter.value
                    }
                });
                records.value = response.data.data;
                totalPages.value = response.data.last_page;
            } catch (error) {
                console.error('Error fetching medical records:', error);
            }
        };

        const viewRecord = async (record) => {
            try {
                const response = await axios.get(`/api/patient/medical-records/${record.id}`);
                selectedRecord.value = response.data;
            } catch (error) {
                console.error('Error fetching record details:', error);
            }
        };

        const downloadRecord = async (record) => {
            try {
                const response = await axios.get(`/api/patient/medical-records/${record.id}/download`, {
                    responseType: 'blob'
                });
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `medical-record-${record.id}.pdf`);
                document.body.appendChild(link);
                link.click();
                link.remove();
            } catch (error) {
                console.error('Error downloading record:', error);
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
            fetchRecords();
        };

        const filteredRecords = computed(() => {
            return records.value;
        });

        onMounted(() => {
            fetchRecords();
        });

        return {
            records,
            selectedRecord,
            searchQuery,
            typeFilter,
            dateFilter,
            currentPage,
            totalPages,
            filteredRecords,
            viewRecord,
            downloadRecord,
            formatDate,
            changePage
        };
    }
};
</script>

<style scoped>
.medical-records {
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

.type-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.875rem;
}

.type-badge.consultation { background: #3498db; color: white; }
.type-badge.lab_result { background: #2ecc71; color: white; }
.type-badge.prescription { background: #9b59b6; color: white; }
.type-badge.procedure { background: #e67e22; color: white; }

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

.record-details {
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

.attachments {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.attachment-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #3498db;
    text-decoration: none;
}

.attachment-link:hover {
    text-decoration: underline;
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
