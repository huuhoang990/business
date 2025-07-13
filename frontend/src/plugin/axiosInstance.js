import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import router from '@/router/index';
import { getCookie } from '@/utils/helper';

const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL+'/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Access-Control-Allow-Origin': '*',
    },
});

axiosInstance.interceptors.request.use(
    config => {
        const xsrfToken = getCookie('XSRF-TOKEN');
        if (xsrfToken) {
            axiosInstance.defaults.headers.common['X-XSRF-TOKEN'] = xsrfToken;
        }
        const auth = useAuthStore();
        if (auth.token) {
            config.headers['Authorization'] = `Bearer ${auth.token}`;
        }
        return config;
    },
    error => {
        Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    response => {
        return response;
    },
    async error => {
        if (error.response && error.response.status === 401) {
            const auth = useAuthStore();
            auth.removeToken();
            router.push({ name: 'login' });
        }
        return Promise.reject(error);
    }
);

export default axiosInstance;