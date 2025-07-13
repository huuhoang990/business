import axiosInstance from '@/plugin/axiosInstance';

export const getListUsersByCondition = async (formSearchUser) => {
    const response = await axiosInstance.get(`/getListUsersByCondition`, {
        params: formSearchUser
    });
    return response.data;
};

export const createUser = async (userForm) => {
    const response = await axiosInstance.post(`/addUser`, userForm, { withCredentials: true });
    return response.data;
};

export const editUser = async (userForm) => {
    const response = await axiosInstance.post(`/editUser`, userForm, { withCredentials: true });
    return response.data;
};

export const deleteUser = async (userForm) => {
    const response = await axiosInstance.post(`/deleteUser`, userForm);
    return response.data;
};

export const updateActiveUser = async (userForm) => {
    const response = await axiosInstance.post(`/updateActiveUser`, userForm);
    return response.data;
};
