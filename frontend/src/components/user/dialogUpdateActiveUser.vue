<script setup>
import { ref, defineProps } from 'vue';
import { updateActiveUser } from '@/service/UserService.js';
import Loading from '@/components/Loading.vue';

const isShowDialog = ref(true);
const isLoading = ref(false);
const emit = defineEmits(['closeDialog']);
const prop = defineProps({
    updateActiveUserObject: {
        type: Object,
        required: true
    }
});
const userForm = {
    user_id: prop.updateActiveUserObject.userId,
    is_active: !prop.updateActiveUserObject.isActive
};

const handleActive = async () => {
    isLoading.value = true;
    try {
        await updateActiveUser(userForm);
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

const textActiveOrInactive = (isActive) => {
    return isActive ? 'khóa' : 'mở khóa';
};
</script>

<template>
    <Dialog
        header="Nhắc nhở"
        v-model:visible="isShowDialog"
        :style="{ width: '350px' }"
        :modal="true"
        @hide="closeDialog"
        >
        <div class="flex items-center justify-center">
            <span>Bạn có muốn {{ textActiveOrInactive(prop.updateActiveUserObject.isActive) }} thành viên {{ prop.updateActiveUserObject.name }} không?</span>
        </div>
        <template #footer>
            <Button label="OK" @click="handleActive" />
            <Button label="Hủy bỏ" @click="closeDialog" severity="secondary" />
        </template>
    </Dialog>
    <Loading :loading="isLoading" />
</template>
