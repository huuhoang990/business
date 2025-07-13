<!-- eslint-disable prettier/prettier -->
<script setup>
import { groupRoleOptions } from '@/utils/dropdownOptions.js';
import { ref, onMounted } from 'vue';
import { createUser } from '@/service/UserService.js';
import { getCsrfCookie } from '@/service/AuthService'
import * as yup from 'yup';
import { formatErrorMessages } from '@/utils/helper.js';
import Loading from '@/components/Loading.vue';

const userForm = ref({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    group_role: null,
    is_active: 0
});
const userFormErrors = ref({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    group_role: ''
});
const schema = yup.object({
    name: yup.string().required('Vui lòng nhập tên người sử dụng').min(5, 'Tên phải lớn hơn 5 ký tự'),
    email: yup.string().required('Email không được để trống.').email('Email không đúng định dạng.'),
    password: yup.string().required('Mật khẩu không được trống')
        .min(5, 'Mật khẩu phải có ít nhất 5 ký tự')
        .matches(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/, 'Mật khẩu không bảo mật'),
    confirm_password: yup.string().required('Xác nhận mật khẩu không được trống').oneOf([yup.ref('password'), null], 'Mật khẩu và xác nhận mật khẩu không chính xác.'),
    group_role: yup.string().required('Vui lòng chọn nhóm người dùng'),
});
const isShowDialog = ref(true);
const emit = defineEmits(['closeDialog']);
const isLoading = ref(false);
const groupRoleList = ref(groupRoleOptions);
const responseError = ref('');

const closeDialogAndUpdateData = () => {
    emit('closeDialog', true);
};

const closeDialog = () => {
    emit('closeDialog', false);
};

const handleSubmit = async () => {
    userFormErrors.value = {
        name: '',
        email: '',
        password: '',
        confirm_password: '',
        group_role: ''
    };
    try {
        await schema.validate(userForm.value, { abortEarly: false });
        handleCreateUser();
    } catch (err) {
        err.inner.forEach((e) => {
            userFormErrors.value[e.path] = e.message;
        });
        return false;
    }
};

const handleCreateUser = async () => {
    isLoading.value = true;
    try {
        await createUser(userForm.value);
        closeDialogAndUpdateData();
    } catch (error) {
        responseError.value = formatErrorMessages(error);
        return false;
    } finally {
        isLoading.value = false;
    }
};

onMounted(async () => {
    isLoading.value = true;
    try {
        await getCsrfCookie();
    } catch (error) {
        responseError.value = formatErrorMessages(error);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <Dialog
        v-model:visible="isShowDialog"
        header="Thêm user"
        :modal="true"
        :draggable="false"
        style="width: 600px"
        :closable="true"
         @hide="closeDialog">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <label for="name_dialogAddUser">Tên</label>
                <InputText id="name_dialogAddUser" type="text" v-model="userForm.name" />
                <Message v-if="userFormErrors.name" severity="error" variant="simple" size="small">{{ userFormErrors.name }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="emailDialogAddUser">Email</label>
                <InputText id="emailDialogAddUser" type="text" v-model="userForm.email" />
                <Message v-if="userFormErrors.email" severity="error" variant="simple" size="small">{{ userFormErrors.email }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="passwordDialogAddUser">Mật khẩu</label>
                <InputText id="passwordDialogAddUser" type="password" v-model="userForm.password" />
                <Message v-if="userFormErrors.password" severity="error" variant="simple" size="small">{{ userFormErrors.password }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="confirmPasswordDialogAddUser">Xác nhận mật khẩu</label>
                <InputText id="confirmPasswordDialogAddUser" type="password" v-model="userForm.confirm_password" />
                <Message v-if="userFormErrors.confirm_password" severity="error" variant="simple" size="small">{{ userFormErrors.confirm_password }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="groupRoleDialogAddUser">Nhóm</label>
                <Select
                    id="groupRoleDialogAddUser"
                    v-model="userForm.group_role"
                    optionLabel="name"
                    optionValue="code"
                    :options="groupRoleList"
                    placeholder="Select" />
                <Message v-if="userFormErrors.group_role" severity="error" variant="simple" size="small">{{ userFormErrors.group_role }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="statusDialogAddUser">Trạng thái</label>
                <Checkbox
                    id="statusDialogAddUser"
                    v-model="userForm.is_active"
                    binary />
            </div>
        </div>
        <Message v-if="responseError" class="my-5" severity="error" variant="simple" size="small">{{ responseError }}</Message>
        <div class="mt-4">
            <Button label="Lưu" severity="success" class="mr-2" @click="handleSubmit" />
            <Button label="Hủy" severity="secondary" @click="closeDialog" />
        </div>
    </Dialog>
    <Loading :loading="isLoading" />
</template>
