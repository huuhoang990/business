import axiosInstance from '@/plugin/axiosInstance';

const login = async (credentials) => {
    const response = await axiosInstance.post(`/login`, credentials, { withCredentials: true });
    return response.data;
};

const logout = async () => {
    try {
        const response = await axiosInstance.post(`/logout`);
        return response;
    } catch (error) {
        return error.response.data;
    }
};

export const getCsrfCookie = async () => {
    await axiosInstance.get(import.meta.env.VITE_API_URL + '/sanctum/csrf-cookie', { withCredentials: true });
}

export default {
    login,
    logout
};
