<template>
    <div class="billing">
        <div class="page-header">
            <h2 class="page-title">Billing</h2>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="card-content">
                    <h3>{{ formatCurrency(totalBilled) }}</h3>
                    <p>Total Billed</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-content">
                    <h3>{{ formatCurrency(totalPaid) }}</h3>
                    <p>Total Paid</p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-content">
                    <h3>{{ formatCurrency(pendingAmount) }}</h3>
                    <p>Pending Amount</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <input type="text" v-model="searchQuery" placeholder="Search bills...">
                <i class="fas fa-search"></i>
            </div>
            <div class="filter-group">
                <select v-model="statusFilter">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
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

        <!-- Bills List -->
        <div class="bills-list">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bill Date</th>
                            <th>Bill Number</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="bill in filteredBills" :key="bill.id">
                            <td>{{ formatDate(bill.date) }}</td>
                            <td>{{ bill.bill_number }}</td>
                            <td>{{ bill.description }}</td>
                            <td>{{ formatCurrency(bill.amount) }}</td>
                            <td>{{ formatDate(bill.due_date) }}</td>
                            <td>
                                <span :class="['status-badge', bill.status.toLowerCase()]">
                                    {{ bill.status }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-sm btn-info" @click="viewBill(bill)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button
                                        v-if="bill.status === 'Pending'"
                                        class="btn btn-sm btn-primary"
                                        @click="payBill(bill)"
                                    >
                                        <i class="fas fa-credit-card"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary" @click="downloadBill(bill)">
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

        <!-- Bill Details Modal -->
        <div class="modal" v-if="selectedBill">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Bill Details</h3>
                    <button class="close-btn" @click="selectedBill = null">&times;</button>
                </div>
                <div class="bill-details">
                    <div class="detail-group">
                        <label>Bill Number</label>
                        <p>{{ selectedBill.bill_number }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Date</label>
                        <p>{{ formatDate(selectedBill.date) }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Due Date</label>
                        <p>{{ formatDate(selectedBill.due_date) }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Status</label>
                        <p>
                            <span :class="['status-badge', selectedBill.status.toLowerCase()]">
                                {{ selectedBill.status }}
                            </span>
                        </p>
                    </div>
                    <div class="detail-group">
                        <label>Description</label>
                        <p>{{ selectedBill.description }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Items</label>
                        <div class="items-list">
                            <div v-for="item in selectedBill.items" :key="item.id" class="item">
                                <div class="item-header">
                                    <span>{{ item.name }}</span>
                                    <span>{{ formatCurrency(item.amount) }}</span>
                                </div>
                                <p class="item-description">{{ item.description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="detail-group total">
                        <label>Total Amount</label>
                        <p>{{ formatCurrency(selectedBill.amount) }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        v-if="selectedBill.status === 'Pending'"
                        class="btn btn-primary"
                        @click="payBill(selectedBill)"
                    >
                        <i class="fas fa-credit-card"></i>
                        Pay Now
                    </button>
                    <button class="btn btn-secondary" @click="downloadBill(selectedBill)">
                        <i class="fas fa-download"></i>
                        Download Bill
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div class="modal" v-if="showPaymentModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Make Payment</h3>
                    <button class="close-btn" @click="showPaymentModal = false">&times;</button>
                </div>
                <form @submit.prevent="processPayment" class="payment-form">
                    <div class="form-group">
                        <label>Amount to Pay</label>
                        <p class="amount">{{ formatCurrency(paymentAmount) }}</p>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select v-model="paymentMethod" required>
                            <option value="">Select Payment Method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="net_banking">Net Banking</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="paymentMethod">
                        <label>Card Number</label>
                        <input type="text" v-model="cardNumber" required placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="form-row" v-if="paymentMethod">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="text" v-model="expiryDate" required placeholder="MM/YY">
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="text" v-model="cvv" required placeholder="123">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" @click="showPaymentModal = false">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="isProcessing">
                            {{ isProcessing ? 'Processing...' : 'Pay Now' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
    name: 'Billing',
    setup() {
        const bills = ref([]);
        const selectedBill = ref(null);
        const showPaymentModal = ref(false);
        const isProcessing = ref(false);
        const searchQuery = ref('');
        const statusFilter = ref('');
        const dateFilter = ref('');
        const currentPage = ref(1);
        const totalPages = ref(1);

        // Payment form data
        const paymentAmount = ref(0);
        const paymentMethod = ref('');
        const cardNumber = ref('');
        const expiryDate = ref('');
        const cvv = ref('');

        // Summary data
        const totalBilled = ref(0);
        const totalPaid = ref(0);
        const pendingAmount = ref(0);

        const fetchBills = async () => {
            try {
                const response = await axios.get('/api/patient/bills', {
                    params: {
                        page: currentPage.value,
                        search: searchQuery.value,
                        status: statusFilter.value,
                        date: dateFilter.value
                    }
                });
                bills.value = response.data.data;
                totalPages.value = response.data.last_page;

                // Update summary data
                totalBilled.value = response.data.summary.total_billed;
                totalPaid.value = response.data.summary.total_paid;
                pendingAmount.value = response.data.summary.pending_amount;
            } catch (error) {
                console.error('Error fetching bills:', error);
            }
        };

        const viewBill = async (bill) => {
            try {
                const response = await axios.get(`/api/patient/bills/${bill.id}`);
                selectedBill.value = response.data;
            } catch (error) {
                console.error('Error fetching bill details:', error);
            }
        };

        const payBill = (bill) => {
            paymentAmount.value = bill.amount;
            selectedBill.value = bill;
            showPaymentModal.value = true;
        };

        const processPayment = async () => {
            isProcessing.value = true;
            try {
                await axios.post(`/api/patient/bills/${selectedBill.value.id}/pay`, {
                    amount: paymentAmount.value,
                    payment_method: paymentMethod.value,
                    card_number: cardNumber.value,
                    expiry_date: expiryDate.value,
                    cvv: cvv.value
                });
                showPaymentModal.value = false;
                fetchBills();
                resetPaymentForm();
            } catch (error) {
                console.error('Error processing payment:', error);
            } finally {
                isProcessing.value = false;
            }
        };

        const downloadBill = async (bill) => {
            try {
                const response = await axios.get(`/api/patient/bills/${bill.id}/download`, {
                    responseType: 'blob'
                });
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `bill-${bill.bill_number}.pdf`);
                document.body.appendChild(link);
                link.click();
                link.remove();
            } catch (error) {
                console.error('Error downloading bill:', error);
            }
        };

        const formatDate = (date) => {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        };

        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        };

        const changePage = (page) => {
            currentPage.value = page;
            fetchBills();
        };

        const resetPaymentForm = () => {
            paymentAmount.value = 0;
            paymentMethod.value = '';
            cardNumber.value = '';
            expiryDate.value = '';
            cvv.value = '';
        };

        const filteredBills = computed(() => {
            return bills.value;
        });

        onMounted(() => {
            fetchBills();
        });

        return {
            bills,
            selectedBill,
            showPaymentModal,
            isProcessing,
            searchQuery,
            statusFilter,
            dateFilter,
            currentPage,
            totalPages,
            totalBilled,
            totalPaid,
            pendingAmount,
            paymentAmount,
            paymentMethod,
            cardNumber,
            expiryDate,
            cvv,
            filteredBills,
            viewBill,
            payBill,
            processPayment,
            downloadBill,
            formatDate,
            formatCurrency,
            changePage
        };
    }
};
</script>

<style scoped>
.billing {
    padding: 1rem;
}

.page-header {
    margin-bottom: 2rem;
}

.summary-cards {
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

.card:nth-child(1) { background: #3498db; }
.card:nth-child(2) { background: #2ecc71; }
.card:nth-child(3) { background: #e67e22; }

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

.status-badge.paid { background: #2ecc71; color: white; }
.status-badge.pending { background: #f1c40f; color: white; }
.status-badge.overdue { background: #e74c3c; color: white; }

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

.bill-details {
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

.items-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.item {
    background: #f8f9fa;
    border-radius: 5px;
    padding: 0.75rem;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
}

.item-description {
    font-size: 0.875rem;
    color: #666;
    margin: 0;
}

.total {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.total p {
    font-size: 1.25rem;
    font-weight: 600;
}

.payment-form {
    margin-top: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.amount {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
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

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
