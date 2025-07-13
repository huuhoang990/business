<script setup>
import { computed, onMounted, ref } from 'vue';
import { statusOptions } from '@/utils/dropdownOptions.js';
import { getListCustomersByCondition, exportCustomerExcel, importCustomerExcel } from '@/service/CustomerService.js';
import { useToast } from 'primevue/usetoast';
import { formatErrorMessages } from '@/utils/helper.js';
import DialogAddCustomer from '@/components/customer/dialogAddCustomer.vue';
import DialogEditCustomer from '@/components/customer/dialogEditCustomer.vue';
import Loading from '@/components/Loading.vue';

const formSearchCustomer = ref({
    customer_name: '',
    email: '',
    address: '',
    is_active: null,
    page: 1
});
const listCustomer = ref([]);
let rowPerPage = ref(0);
let totalRecord = ref(0);
const toast = useToast();
const isLoading = ref(false);
const isShowDialogAddCustomer = ref(false);
const isShowDialogEditCustomer = ref(false);
const editCustomerObject = {
    customer_id: null,
    customer_name: '',
    email: '',
    tel_num: '',
    address: '',
    is_active: null
};
const openDialogAddCustomer = () => {
    isShowDialogAddCustomer.value = true;
};
const closeDialogAddCustomer = (isUpdateData) => {
    isShowDialogAddCustomer.value = false;
    if (isUpdateData) {
        fetchCustomerList();
    }
};
const resetFormSearchCustomer = () => {
    formSearchCustomer.value.customer_name = '';
    formSearchCustomer.value.email = '';
    formSearchCustomer.value.address = '';
    formSearchCustomer.value.is_active = null;
    formSearchCustomer.value.page = 1;
    fetchCustomerList();
};
const fileImportExcel = ref(null);

const fetchCustomerList = async () => {
    isLoading.value = true;
    try {
        const response = await getListCustomersByCondition(formSearchCustomer.value);
        if (response && response.data) {
            listCustomer.value = response.data.list_customers;
            rowPerPage.value = response.data.pagination.row_per_page;
            totalRecord.value = response.data.pagination.total_records;
        } else {
            listCustomer.value = [];
            rowPerPage.value = 0;
            totalRecord.value = 0;
        }
    } catch (error) {
        const errorMsg = formatErrorMessages(error);
        toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 5000 });
        listCustomer.value = [];
        rowPerPage.value = 0;
        totalRecord.value = 0;
    } finally {
        isLoading.value = false;
    }
};

const onPageChange = (event) => {
    formSearchCustomer.value.page = event.page + 1;
    fetchCustomerList();
};

const infoRecordTable = computed(() => {
    if (totalRecord.value === 0) {
        return 'Không có dữ liệu';
    } else if (totalRecord.value < rowPerPage.value) {
        return `Hiển thị từ 1 ~ ${totalRecord.value} trong tổng số ${totalRecord.value} user`;
    } else {
        return `Hiển thị từ 1 ~ ${rowPerPage.value} trong tổng số ${totalRecord.value} user`;
    }
});

const shouldShowPaginator = computed(() => {
    return totalRecord.value >= rowPerPage.value;
});

const openDialogEditUser = (rowData) => {
    editCustomerObject.customer_id = rowData.customer_id;
    editCustomerObject.customer_name = rowData.customer_name;
    editCustomerObject.email = rowData.email;
    editCustomerObject.tel_num = rowData.tel_num;
    editCustomerObject.address = rowData.address;
    editCustomerObject.is_active = rowData.is_active;
    isShowDialogEditCustomer.value = true;
};

const closeDialogEditUser = (isUpdateData) => {
    isShowDialogEditCustomer.value = false;
    if (isUpdateData) {
        fetchCustomerList();
    }
};

const exportExcel = async () => {
    isLoading.value = true;
    try {
        const response = await exportCustomerExcel(formSearchCustomer.value);
        if (response && response.data) {
            const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'customers.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            toast.add({ severity: 'warn', summary: 'Warning', detail: 'Không có dữ liệu để xuất', life: 5000 });
        }
    } catch (error) {
        const errorMsg = formatErrorMessages(error);
        toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 5000 });
    } finally {
        isLoading.value = false;
    }
};

const triggerFileImportExcel = () => {
    fileImportExcel.value.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    importCustomerFile(file);
};

const importCustomerFile = async (file) => {
    isLoading.value = true;
    try {
        const formData = new FormData();
        formData.append('import_customer_file', file);
        const response = await importCustomerExcel(formData);
        if (response && response.code === 200) {
            toast.add({ severity: 'success', summary: 'Success', detail: response.message, life: 5000 });
        }

        fetchCustomerList();
    } catch (error) {
        const errorMsg = formatErrorMessages(error);
        toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 5000 });
    } finally {
        isLoading.value = false;
        fileImportExcel.value.value = ''; 
    }
};

onMounted(() => {
    fetchCustomerList();
});
</script>

<template>
    <div>
        <div class="card flex flex-col gap-4">
            <div class="font-semibold text-xl">Tìm kiếm</div>
            <div class="flex flex-wrap gap-4">
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="name">Tên</label>
                    <InputText id="name" type="text" v-model="formSearchCustomer.customer_name" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="email">Email</label>
                    <InputText id="email" type="text" v-model="formSearchCustomer.email" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="font-semibold">Trạng thái</div>
                    <Select
                        optionLabel="name"
                        optionValue="code"
                        :options="statusOptions"
                        v-model="formSearchCustomer.is_active"
                        placeholder="Select" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="address">Địa chỉ</label>
                    <InputText id="address" type="text" v-model="formSearchCustomer.address" />
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <Button label="Tìm kiếm" severity="info" @click="fetchCustomerList" />
                <Button label="Xóa tìm" severity="secondary" @click="resetFormSearchCustomer" />
            </div>
        </div>
        <div class="card flex flex-col gap-4">
            <div>
                <Button
                    label="Thêm mới"
                    icon="pi pi-plus"
                    severity="info"
                    class="mr-2"
                    @click="openDialogAddCustomer" />
                <Button
                    label="Import Excel"
                    icon="pi pi-upload"
                    severity="Primary"
                    class="mr-2"
                    @click="triggerFileImportExcel" />
                <Button
                    label="Export Excel"
                    icon="pi pi-download"
                    severity="Primary"
                    class="mr-2"
                    @click="exportExcel" />
                <input
                    type="file"
                    ref="fileImportExcel"
                    @change="handleFileChange"
                    accept=".xlsx, .xls"
                    class="hidden"
                />
            </div>
            <div class="text-right">
                {{ infoRecordTable }}
            </div>
            <DataTable
                :value="listCustomer"
                :paginator="shouldShowPaginator"
                :lazy="true"
                :rows="rowPerPage"
                :total-records="totalRecord"
                :page="formSearchCustomer.page - 1"
                @page="onPageChange"
                tableStyle="min-width: 50rem">
                <Column field="row_num" header="#"></Column>
                <Column field="customer_name" header="Họ tên"></Column>
                <Column field="email" header="Email"></Column>
                <Column field="address" header="Địa chỉ"></Column>
                <Column field="tel_num" header="Điện thoại"></Column>
                <Column header="">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <Button icon="pi pi-pencil" @click="openDialogEditUser(slotProps.data)" />
                        </div>
                    </template>
                </Column>
                <template #empty>
                    <div class="p-d-flex p-jc-center p-ai-center">
                        <p>Không có dữ liệu.</p>
                    </div>
                </template>
            </DataTable>
        </div>
        <DialogAddCustomer v-if="isShowDialogAddCustomer" @closeDialog="closeDialogAddCustomer" />
        <DialogEditCustomer v-if="isShowDialogEditCustomer" :editCustomerObject="editCustomerObject" @closeDialog="closeDialogEditUser" />
        <Loading :loading="isLoading" />
    </div>
</template>
