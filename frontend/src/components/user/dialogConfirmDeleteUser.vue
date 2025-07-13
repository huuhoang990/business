<script setup>
import { ref, defineProps } from 'vue';
import { deleteUser } from '@/service/UserService.js';
import Loading from '@/components/Loading.vue';

const isShowDialog = ref(true);
const isLoading = ref(false);
const emit = defineEmits(['closeDialog']);
const prop = defineProps({
    deleteUserObject: {
        type: Object,
        required: true
    }
});
const userForm = {
    user_id: prop.deleteUserObject.userId
};

const handleDeleteUser = async () => {
    isLoading.value = true;
    try {
        await deleteUser(userForm);
    } catch (error) {
        console.error('Error fetching user list:', error);
    } finally {
        isLoading.value = false;
    }
    closeDialogAndUpdateData();
};

const closeDialogAndUpdateData = () => {
    emit('closeDialog', true);
};

const closeDialog = () => {
    emit('closeDialog', false);
};
</script>

<template>
    <Dialog
        header="Nhắc nhở"
        v-model:visible="isShowDialog"
        :style="{ width: '350px' }"
        :modal="true"
        @hide="closeDialog">
        <div class="flex items-center justify-center">
            <span>Bạn có muốn xoá thành viên {{ prop.deleteUserObject.name }} không?</span>
        </div>
        <template #footer>
            <Button label="OK" @click="handleDeleteUser" />
            <Button label="Hủy bỏ" @click="closeDialog" severity="secondary" />
        </template>
    </Dialog>
    <Loading :loading="isLoading" />
</template>
