import AuthService from '@/service/AuthService';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    // const user = ref(null);
    const token = ref(localStorage.getItem('token') || null);

    const isAuthenticated = ref(!!token.value);

    const login = async (loginForm) => {
        try {
            const response = await AuthService.login(loginForm);
            // user.value = response.data.user;
            token.value = response.data.token;
            isAuthenticated.value = true;
            localStorage.setItem('token', token.value);
        } catch (error) {
            console.error('Login failed:', error);
            throw error;
        }
    };

    const logout = async () => {
        try {
            await AuthService.logout();
            token.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem('token');
        } catch (error) {
            console.error('Logout failed:', error);
            throw error;
        }
    };

    const removeToken = () => {
        token.value = null;
        isAuthenticated.value = false;
        localStorage.removeItem('token');
    };

    return {
        //user,
        token,
        isAuthenticated,
        login,
        logout,
        removeToken
    };
});
