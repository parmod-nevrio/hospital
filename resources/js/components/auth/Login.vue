<template>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Please sign in to your account</p>
            </div>

            <form @submit.prevent="handleLogin" class="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        autocomplete="email"
                        placeholder="Enter your email"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" v-model="form.remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <div class="form-actions">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="isLoading"
                    >
                        {{ isLoading ? 'Signing in...' : 'Sign In' }}
                    </button>
                </div>

                <div v-if="error" class="error-message">
                    {{ error }}
                </div>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="/register">Register</a></p>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter, useRoute } from 'vue-router';

export default {
    name: 'Login',
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();
        const route = useRoute();

        const form = ref({
            email: '',
            password: '',
            remember: false
        });

        const isLoading = ref(false);
        const error = ref('');

        const handleLogin = async () => {
            isLoading.value = true;
            error.value = '';

            try {
                await authStore.login(form.value);

                // Redirect to the intended page or dashboard
                const redirectPath = route.query.redirect || '/patient/dashboard';
                router.push(redirectPath);
            } catch (err) {
                error.value = err.response?.data?.message || 'An error occurred during login';
            } finally {
                isLoading.value = false;
            }
        };

        return {
            form,
            isLoading,
            error,
            handleLogin
        };
    }
};
</script>

<style scoped>
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    padding: 1rem;
}

.login-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    width: 100%;
    max-width: 400px;
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-header h2 {
    color: #2c3e50;
    margin: 0;
    font-size: 1.8rem;
}

.login-header p {
    color: #666;
    margin: 0.5rem 0 0;
}

.login-form {
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    border-color: #3498db;
    outline: none;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
}

.forgot-password {
    color: #3498db;
    text-decoration: none;
}

.forgot-password:hover {
    text-decoration: underline;
}

.form-actions {
    margin-bottom: 1rem;
}

.btn {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
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

.error-message {
    color: #e74c3c;
    text-align: center;
    margin-top: 1rem;
    padding: 0.5rem;
    background: #fde8e8;
    border-radius: 5px;
}

.login-footer {
    text-align: center;
    color: #666;
}

.login-footer a {
    color: #3498db;
    text-decoration: none;
}

.login-footer a:hover {
    text-decoration: underline;
}

@media (max-width: 480px) {
    .login-card {
        padding: 1.5rem;
    }

    .form-options {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>
