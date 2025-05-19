<template>
    <div class="patient-layout">
        <!-- Sidebar -->
        <aside class="sidebar" :class="{ 'active': isSidebarActive }">
            <div class="sidebar-header">
                <img src="/images/logo.png" alt="Hospital Logo" class="logo">
                <h5>Patient Portal</h5>
            </div>
            <nav class="sidebar-nav">
                <router-link to="/patient/dashboard" class="nav-item" active-class="active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </router-link>
                <router-link to="/patient/appointments" class="nav-item" active-class="active">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </router-link>
                <router-link to="/patient/medical-records" class="nav-item" active-class="active">
                    <i class="fas fa-file-medical"></i>
                    <span>Medical Records</span>
                </router-link>
                <router-link to="/patient/prescriptions" class="nav-item" active-class="active">
                    <i class="fas fa-prescription"></i>
                    <span>Prescriptions</span>
                </router-link>
                <router-link to="/patient/billing" class="nav-item" active-class="active">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Billing</span>
                </router-link>
                <router-link to="/patient/profile" class="nav-item" active-class="active">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </router-link>
                <a @click="logout" class="nav-item text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <button class="sidebar-toggle" @click="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-info">
                    <img :src="user?.profile_photo_url" :alt="user?.name" class="avatar">
                    <div class="user-details">
                        <h6>{{ user?.name }}</h6>
                        <small>Patient ID: {{ user?.patient_id }}</small>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
    name: 'PatientLayout',
    setup() {
        const store = useStore();
        const router = useRouter();
        const isSidebarActive = ref(false);

        const user = computed(() => store.state.user);

        const toggleSidebar = () => {
            isSidebarActive.value = !isSidebarActive.value;
        };

        const logout = async () => {
            await store.dispatch('logout');
            router.push('/login');
        };

        return {
            user,
            isSidebarActive,
            toggleSidebar,
            logout
        };
    }
};
</script>

<style scoped>
.patient-layout {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 1rem;
    transition: all 0.3s ease;
}

.sidebar-header {
    text-align: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.sidebar-nav {
    margin-top: 2rem;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.8rem 1rem;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-item:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.nav-item.active {
    background: #3498db;
    color: white;
}

.nav-item i {
    width: 25px;
    margin-right: 0.5rem;
}

.main-content {
    flex: 1;
    background: #f8f9fa;
}

.top-bar {
    background: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sidebar-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #2c3e50;
    cursor: pointer;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-details h6 {
    margin: 0;
    color: #2c3e50;
}

.user-details small {
    color: #6c757d;
}

.content {
    padding: 2rem;
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: -250px;
        height: 100vh;
        z-index: 1000;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-toggle {
        display: block;
    }

    .main-content {
        margin-left: 0;
    }
}
</style>
