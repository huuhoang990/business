<script setup>
import { ref } from 'vue';
import { createCustomer } from '@/service/CustomerService.js';
import * as yup from 'yup';
import { formatErrorMessages } from '@/utils/helper.js';
import Loading from '@/components/Loading.vue';

const userForm = ref({
    customer_name: '',
    email: '',
    tel_num: '',
    address: '',
    is_active: 0,
});
const userFormErrors = ref({
    customer_name: '',
    email: '',
    tel_num: '',
    address: '',
});
const schema = yup.object({
    customer_name: yup.string().required('Vui lòng nhập tên khách hàng.').min(5, 'Tên khách hàng phải hơn 5 ký tự.'),
    email: yup.string().required('Email không được để trống.').email('Email không đúng định dạng.'),
    tel_num: yup
        .string()
        .required('Điện thoại không được để trống.')
        .matches(/^\d{10}$/, 'Nhập không đúng định dạng điện thoại.'),
    address: yup.string().required('Địa chỉ không được để trống.')
});
const isShowDialog = ref(true);
const emit = defineEmits(['closeDialog']);
const isLoading = ref(false);
const responseError = ref('');

const closeDialogAndUpdateData = () => {
    emit('closeDialog', true);
};

const closeDialog = () => {
    emit('closeDialog', false);
};

const handleSubmit = async () => {
    userFormErrors.value = {
        customer_name: '',
        email: '',
        tel_num: '',
        address: ''
    };
    responseError.value = '';
    try {
        await schema.validate(userForm.value, { abortEarly: false });
        handleCreateCustomer();
    } catch (err) {
        err.inner.forEach((e) => {
            userFormErrors.value[e.path] = e.message;
        });
        return false;
    }
};

const handleCreateCustomer = async () => {
    isLoading.value = true;
    responseError.value = '';
    try {
        await createCustomer(userForm.value);
        closeDialogAndUpdateData();
    } catch (error) {
        responseError.value = formatErrorMessages(error);
        return false;
    } finally {
        isLoading.value = false;
    }
};

</script>

<template>
    <Dialog
        v-model:visible="isShowDialog"
        header="Thêm khách hàng"
        :modal="true"
        :draggable="false"
        style="width: 600px"
        :closable="true"
         @hide="closeDialog">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <label for="nameDialogAddCustomer">Tên</label>
                <InputText id="nameDialogAddCustomer" type="text" v-model="userForm.customer_name" />
                <Message v-if="userFormErrors.customer_name" severity="error" variant="simple" size="small">{{ userFormErrors.customer_name }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="emailDialogAddCustomer">Email</label>
                <InputText id="emailDialogAddCustomer" type="text" v-model="userForm.email" />
                <Message v-if="userFormErrors.email" severity="error" variant="simple" size="small">{{ userFormErrors.email }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="emailDialogAddCustomer">Điện thoại</label>
                <InputText id="emailDialogAddCustomer" type="text" v-model="userForm.tel_num" />
                <Message v-if="userFormErrors.tel_num" severity="error" variant="simple" size="small">{{ userFormErrors.tel_num }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="emailDialogAddCustomer">Địa chỉ</label>
                <InputText id="emailDialogAddCustomer" type="text" v-model="userForm.address" />
                <Message v-if="userFormErrors.address" severity="error" variant="simple" size="small">{{ userFormErrors.address }}</Message>
            </div>
            <div class="flex flex-col gap-2">
                <label for="statusDialogAddCustomer">Trạng thái</label>
                <Checkbox
                    id="statusDialogAddCustomer"
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
