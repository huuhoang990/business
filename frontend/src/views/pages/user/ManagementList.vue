<script setup>
import Loading from '@/components/Loading.vue';
import { getListUsersByCondition } from '@/service/UserService';
import { groupRoleOptions, statusOptions } from '@/utils/dropdownOptions.js';
import { computed, onMounted, ref } from 'vue';
import DialogAddUser from '@/components/user/dialogAddUser.vue';
import DialogEditUser from '@/components/user/dialogEditUser.vue';
import DialogConfirmDeleteUser from '@/components/user/dialogConfirmDeleteUser.vue';
import DialogUpdateActiveUser from '@/components/user/dialogUpdateActiveUser.vue';
import { useToast } from 'primevue/usetoast';
import { formatErrorMessages } from '@/utils/helper.js';

const toast = useToast();
const groupRoleList = ref(groupRoleOptions);
const statusList = ref(statusOptions);
const listUser = ref([]);
let rowPerPage = ref(0);
let totalRecord = ref(0);
const isLoading = ref(false);
const isShowDialogAddUser = ref(false);
const isShowDialogDeleteUser = ref(false);
const isShowDialogUpdateActiveUser = ref(false);
const addUserObject = {
    isEdit: false,
    userId: null
};
const isShowDialogEditUser = ref(false);
const editUserObject = {
    userId: null,
    name: '',
    email: '',
    group_role: null,
    is_active: null,
};
const formSearchUser = ref({
    name: '',
    email: '',
    group_role: null,
    is_active: null,
    page: 1
});
const deleteUserObject = {
    userId: null,
    name: ''
};
const updateActiveUserObject = {
    userId: null,
    name: '',
    isActive: null
};
const resetFormSearchUser = () => {
    formSearchUser.value.name = '';
    formSearchUser.value.email = '';
    formSearchUser.value.group_role = null;
    formSearchUser.value.is_active = null;
    formSearchUser.value.page = 1;
    fetchUserList();
};

const fetchUserList = async () => {
    isLoading.value = true;
    try {
        const response = await getListUsersByCondition(formSearchUser.value);
        if (response && response.data) {
            listUser.value = response.data.list_users;
            totalRecord.value = response.data.pagination.total_records;
            rowPerPage.value = response.data.pagination.row_per_page;
        } else {
            listUser.value = [];
            totalRecord.value = 0;
            rowPerPage.value = 0;
        }
    } catch (error) {
        const errorMsg = formatErrorMessages(error);
        toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 5000 });
    } finally {
        isLoading.value = false;
    }
};

const onPageChange = (event) => {
    formSearchUser.value.page = event.page + 1;
    fetchUserList();
};

const getStatusName = (statusCode) => {
    return statusOptions.find((s) => s.code === String(statusCode)).name;
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

const openDialogAddUser = () => {
    addUserObject.userId = null;
    isShowDialogAddUser.value = true;
};

const openDialogEditUser = (rowData) => {
    editUserObject.userId = rowData.user_id;
    editUserObject.name = rowData.name;
    editUserObject.email = rowData.email;
    editUserObject.group_role = groupRoleList.value.find((option) => option.name === rowData.group_role)?.code || null;
    editUserObject.is_active = rowData.is_active;
    isShowDialogEditUser.value = true;
};

const openDialogDeleteUser = (rowData) => {
    deleteUserObject.userId = rowData.user_id;
    deleteUserObject.name = rowData.name;
    isShowDialogDeleteUser.value = true;
};

const openDialogUpdateActiveUser = (rowData) => {
    updateActiveUserObject.userId = rowData.user_id;
    updateActiveUserObject.name = rowData.name;
    updateActiveUserObject.isActive = rowData.is_active;
    isShowDialogUpdateActiveUser.value = true;
};

const closeDialogAddUser = (isUpdateData) => {
    isShowDialogAddUser.value = false;
    if (isUpdateData) {
        fetchUserList();
    }
};

const closeDialogEditUser = (isUpdateData) => {
    editUserObject.userId = null;
    editUserObject.name = '';
    editUserObject.email = '';
    editUserObject.group_role = null;
    isShowDialogEditUser.value = false;
    if (isUpdateData) {
        fetchUserList();
    }
};

const closeDialogDeleteUser = (isUpdateData) => {
    isShowDialogDeleteUser.value = false;
    if (isUpdateData) {
        fetchUserList();
    }
};

const closeDialogUpdateActiveUser = (isUpdateData) => {
    isShowDialogUpdateActiveUser.value = false;
    if (isUpdateData) {
        fetchUserList();
    }
};

const shouldShowPaginator = computed(() => {
    return totalRecord.value >= rowPerPage.value;
});

onMounted(() => {
    fetchUserList();
});
</script>

<template>
    <div>
        <div class="card flex flex-col gap-4">
            <div class="font-semibold text-xl">Tìm kiếm</div>
            <div class="flex flex-wrap gap-4">
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="name">Tên</label>
                    <InputText id="name" type="text" v-model="formSearchUser.name" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="email">Email</label>
                    <InputText id="email" type="text" v-model="formSearchUser.email" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="font-semibold">Nhóm</div>
                    <Select
                        optionLabel="name"
                        optionValue="code"
                        :options="groupRoleList"
                        v-model="formSearchUser.group_role"
                        placeholder="Select" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="font-semibold">Trạng thái</div>
                    <Select
                        optionLabel="name"
                        optionValue="code"
                        :options="statusList"
                        v-model="formSearchUser.is_active"
                        placeholder="Select" />
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <Button label="Tìm kiếm" severity="info" @click="fetchUserList" />
                <Button label="Xóa tìm" severity="secondary" @click="resetFormSearchUser" />
            </div>
        </div>
        <div class="card flex flex-col gap-4">
            <div>
                <Button
                    label="Thêm mới"
                    icon="pi pi-plus"
                    severity="info"
                    class="mr-2"
                    @click="openDialogAddUser()" />
            </div>
            <div class="text-right">
                {{ infoRecordTable }}
            </div>
            <DataTable
                :value="listUser"
                :paginator="shouldShowPaginator"
                :lazy="true"
                :rows="rowPerPage"
                :total-records="totalRecord"
                :page="formSearchUser.page - 1"
                @page="onPageChange"
                tableStyle="min-width: 50rem">
                <Column field="row_num" header="#"></Column>
                <Column field="name" header="Họ tên"></Column>
                <Column field="email" header="Email"></Column>
                <Column field="group_role" header="Nhóm"></Column>
                <Column header="Trạng thái">
                    <template #body="slotProps">
                        {{ getStatusName(slotProps.data.is_active) }}
                    </template>
                </Column>
                <Column header="">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <Button icon="pi pi-pencil" @click="openDialogEditUser(slotProps.data)" />
                            <Button icon="pi pi-trash" severity="danger" @click="openDialogDeleteUser(slotProps.data)" />
                            <Button icon="pi pi-user-edit" severity="info" @click="openDialogUpdateActiveUser(slotProps.data)" />
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
        <DialogAddUser v-if="isShowDialogAddUser" @closeDialog="closeDialogAddUser" />
        <DialogEditUser v-if="isShowDialogEditUser" :editUserObject="editUserObject" @closeDialog="closeDialogEditUser" />
        <DialogConfirmDeleteUser v-if="isShowDialogDeleteUser" :deleteUserObject="deleteUserObject" @closeDialog="closeDialogDeleteUser" />
        <DialogUpdateActiveUser
            v-if="isShowDialogUpdateActiveUser"
            :updateActiveUserObject="updateActiveUserObject"
            @closeDialog="closeDialogUpdateActiveUser" />
        <Loading :loading="isLoading" />
    </div>
</template>
