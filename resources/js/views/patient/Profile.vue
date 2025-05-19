<template>
    <div class="profile">
        <div class="page-header">
            <h2 class="page-title">Profile</h2>
        </div>

        <div class="profile-content">
            <!-- Profile Information -->
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-image">
                        <img :src="user?.profile_photo_url" :alt="user?.name">
                        <button class="change-photo-btn" @click="triggerFileInput">
                            <i class="fas fa-camera"></i>
                        </button>
                        <input
                            type="file"
                            ref="fileInput"
                            accept="image/*"
                            style="display: none"
                            @change="handlePhotoChange"
                        >
                    </div>
                    <div class="profile-info">
                        <h3>{{ user?.name }}</h3>
                        <p class="patient-id">Patient ID: {{ user?.patient_id }}</p>
                    </div>
                </div>

                <form @submit.prevent="updateProfile" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" v-model="form.first_name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" v-model="form.last_name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" v-model="form.email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" v-model="form.phone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" v-model="form.date_of_birth" required>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select v-model="form.gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea v-model="form.address" rows="3" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" v-model="form.city" required>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" v-model="form.state" required>
                        </div>
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="text" v-model="form.zip_code" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                            {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Emergency Contact -->
            <div class="profile-section">
                <h3>Emergency Contact</h3>
                <form @submit.prevent="updateEmergencyContact" class="emergency-contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" v-model="emergencyContact.name" required>
                        </div>
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" v-model="emergencyContact.relationship" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" v-model="emergencyContact.phone" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" v-model="emergencyContact.email">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                            {{ isSubmitting ? 'Saving...' : 'Save Emergency Contact' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Medical Information -->
            <div class="profile-section">
                <h3>Medical Information</h3>
                <form @submit.prevent="updateMedicalInfo" class="medical-info-form">
                    <div class="form-group">
                        <label>Blood Type</label>
                        <select v-model="medicalInfo.blood_type" required>
                            <option value="">Select Blood Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Allergies</label>
                        <textarea v-model="medicalInfo.allergies" rows="3" placeholder="List any allergies..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Medical Conditions</label>
                        <textarea v-model="medicalInfo.conditions" rows="3" placeholder="List any medical conditions..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Current Medications</label>
                        <textarea v-model="medicalInfo.medications" rows="3" placeholder="List any current medications..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                            {{ isSubmitting ? 'Saving...' : 'Save Medical Information' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';

export default {
    name: 'Profile',
    setup() {
        const store = useStore();
        const fileInput = ref(null);
        const isSubmitting = ref(false);

        const user = ref(store.state.user);

        const form = ref({
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            date_of_birth: '',
            gender: '',
            address: '',
            city: '',
            state: '',
            zip_code: ''
        });

        const emergencyContact = ref({
            name: '',
            relationship: '',
            phone: '',
            email: ''
        });

        const medicalInfo = ref({
            blood_type: '',
            allergies: '',
            conditions: '',
            medications: ''
        });

        const fetchProfile = async () => {
            try {
                const response = await axios.get('/api/patient/profile');
                const data = response.data;

                // Update form data
                form.value = {
                    first_name: data.first_name,
                    last_name: data.last_name,
                    email: data.email,
                    phone: data.phone,
                    date_of_birth: data.date_of_birth,
                    gender: data.gender,
                    address: data.address,
                    city: data.city,
                    state: data.state,
                    zip_code: data.zip_code
                };

                // Update emergency contact
                emergencyContact.value = data.emergency_contact || {};

                // Update medical info
                medicalInfo.value = data.medical_info || {};
            } catch (error) {
                console.error('Error fetching profile:', error);
            }
        };

        const updateProfile = async () => {
            isSubmitting.value = true;
            try {
                await axios.put('/api/patient/profile', form.value);
                // Update user in store
                store.commit('setUser', { ...user.value, ...form.value });
            } catch (error) {
                console.error('Error updating profile:', error);
            } finally {
                isSubmitting.value = false;
            }
        };

        const updateEmergencyContact = async () => {
            isSubmitting.value = true;
            try {
                await axios.put('/api/patient/emergency-contact', emergencyContact.value);
            } catch (error) {
                console.error('Error updating emergency contact:', error);
            } finally {
                isSubmitting.value = false;
            }
        };

        const updateMedicalInfo = async () => {
            isSubmitting.value = true;
            try {
                await axios.put('/api/patient/medical-info', medicalInfo.value);
            } catch (error) {
                console.error('Error updating medical info:', error);
            } finally {
                isSubmitting.value = false;
            }
        };

        const triggerFileInput = () => {
            fileInput.value.click();
        };

        const handlePhotoChange = async (event) => {
            const file = event.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('photo', file);

                try {
                    const response = await axios.post('/api/patient/profile/photo', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    // Update user photo in store
                    store.commit('setUser', { ...user.value, profile_photo_url: response.data.photo_url });
                } catch (error) {
                    console.error('Error uploading photo:', error);
                }
            }
        };

        onMounted(() => {
            fetchProfile();
        });

        return {
            user,
            form,
            emergencyContact,
            medicalInfo,
            isSubmitting,
            fileInput,
            updateProfile,
            updateEmergencyContact,
            updateMedicalInfo,
            triggerFileInput,
            handlePhotoChange
        };
    }
};
</script>

<style scoped>
.profile {
    padding: 1rem;
}

.page-header {
    margin-bottom: 2rem;
}

.profile-content {
    display: grid;
    gap: 2rem;
}

.profile-section {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.profile-image {
    position: relative;
    width: 150px;
    height: 150px;
}

.profile-image img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.change-photo-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #3498db;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.profile-info h3 {
    margin: 0;
    color: #2c3e50;
}

.patient-id {
    color: #666;
    margin: 0.5rem 0 0;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
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
    margin-top: 1.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-primary:disabled {
    background: #bdc3c7;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
