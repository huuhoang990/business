<!-- eslint-disable prettier/prettier -->
<script setup>
import { groupRoleOptions } from '@/utils/dropdownOptions.js';
import { ref, defineProps, onMounted } from 'vue';
import { editUser } from '@/service/UserService.js';
import * as yup from 'yup';
import { formatErrorMessages } from '@/utils/helper.js';
import Loading from '@/components/Loading.vue';
import { getCsrfCookie } from '@/service/AuthService.js';

const prop = defineProps({
    editUserObject: {
        type: Object,
        required: true
    }
});

const userForm = ref({
    user_id: prop.editUserObject.userId,
    name: prop.editUserObject.name,
    email: prop.editUserObject.email,
    password: '',
    confirm_password: '',
    group_role: prop.editUserObject.group_role,
    is_active: prop.editUserObject.is_active
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
    password: yup.string()
        .when({
            is: (val) => val && val.length > 0,
            then: (schema) => schema
                .min(5, 'Mật khẩu phải có ít nhất 5 ký tự')
                .matches(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/, 'Mật khẩu không bảo mật')
        })
        .nullable(),
    confirm_password: yup.string().when('password', {
            is: (val) => val && val.length > 0,
            then: (schema) => schema
                .required('Vui lòng xác nhận mật khẩu.')
                .oneOf([yup.ref('password')], 'Mật khẩu và xác nhận mật khẩu không chính xác.')
        })
        .nullable(),
    group_role: yup.string().required('Vui lòng chọn nhóm người dùng'),
});
const isShowDialog = ref(true);
const emit = defineEmits(['closeDialog']);
const isLoading = ref(false);
const responseError = ref('');
const groupRoleList = ref(groupRoleOptions);

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
        handleEditUser();
    } catch (err) {
        err.inner.forEach((e) => {
            userFormErrors.value[e.path] = e.message;
        });
        return false;
    }
};

const handleEditUser = async () => {
    isLoading.value = true;
    try {
        await editUser(userForm.value);
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
        header="Sửa user"
        :modal="true"
        :draggable="false"
        style="width: 600px"
        :closable="true"
         @hide="closeDialog">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <label for="nameDialogEditUser">Tên</label>
                <InputText id="nameDialogEditUser" type="text" v-model="userForm.name" />
                <Message v-if="userFormErrors.name" severity="error" variant="simple" size="small">{{ userFormErrors.name }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="emailDialogEditUser">Email</label>
                <InputText id="emailDialogEditUser" type="text" v-model="userForm.email" />
                <Message v-if="userFormErrors.email" severity="error" variant="simple" size="small">{{ userFormErrors.email }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="passwordDialogEditUser">Mật khẩu</label>
                <InputText id="passwordDialogEditUser" type="password" v-model="userForm.password" />
                <Message v-if="userFormErrors.password" severity="error" variant="simple" size="small">{{ userFormErrors.password }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="confirmPasswordDialogEditUser">Xác nhận mật khẩu</label>
                <InputText id="confirmPasswordDialogEditUser" type="password" v-model="userForm.confirm_password" />
                <Message v-if="userFormErrors.confirm_password" severity="error" variant="simple" size="small">{{ userFormErrors.confirm_password }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="groupRoleDialogEditUser">Nhóm</label>
                <Select
                    id="groupRoleDialogEditUser"
                    v-model="userForm.group_role"
                    optionLabel="name"
                    optionValue="code"
                    :options="groupRoleList"
                    placeholder="Select" />
                <Message v-if="userFormErrors.group_role" severity="error" variant="simple" size="small">{{ userFormErrors.group_role }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="statusDialogEditUser">Trạng thái</label>
                <Checkbox
                    id="statusDialogEditUser"
                    v-model="userForm.is_active"
                    binary
                    :true-value="1"
                    :false-value="0"
                     />
                <Message v-if="userFormErrors.is_active" severity="error" variant="simple" size="small">{{ userFormErrors.is_active }}</Message>
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
