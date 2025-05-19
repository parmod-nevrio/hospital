import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import axios from 'axios';

// PrimeVue styles
// import '@primevue/themes/lara-light-indigo/theme.css';
// import 'primevue/resources/primevue.css';
// import 'primeicons/primeicons.css';

// PrimeVue Components
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Checkbox from 'primevue/checkbox';

// Import components
import Dashboard from './views/patient/Dashboard.vue';
import Appointments from './views/patient/Appointments.vue';
import MedicalRecords from './views/patient/MedicalRecords.vue';
import Prescriptions from './views/patient/Prescriptions.vue';
import Billing from './views/patient/Billing.vue';
import Profile from './views/patient/Profile.vue';
import Login from './components/auth/Login.vue';

// Configure axios
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { guest: true }
        },
        {
            path: '/patient',
            component: () => import('./layouts/PatientLayout.vue'),
            meta: { requiresAuth: true },
            children: [
                {
                    path: 'dashboard',
                    name: 'patient.dashboard',
                    component: Dashboard
                },
                {
                    path: 'appointments',
                    name: 'patient.appointments',
                    component: Appointments
                },
                {
                    path: 'medical-records',
                    name: 'patient.medical-records',
                    component: MedicalRecords
                },
                {
                    path: 'prescriptions',
                    name: 'patient.prescriptions',
                    component: Prescriptions
                },
                {
                    path: 'billing',
                    name: 'patient.billing',
                    component: Billing
                },
                {
                    path: 'profile',
                    name: 'patient.profile',
                    component: Profile
                }
            ]
        }
    ]
});

// Navigation guard
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!localStorage.getItem('token')) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (localStorage.getItem('token')) {
            next('/patient/dashboard');
        } else {
            next();
        }
    } else {
        next();
    }
});

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(PrimeVue, { ripple: true });
app.use(ToastService);

// Register PrimeVue Components
app.component('InputText', InputText);
app.component('Password', Password);
app.component('Button', Button);
app.component('Card', Card);
app.component('Checkbox', Checkbox);
app.component('Toast', Toast);

app.mount('#app');
