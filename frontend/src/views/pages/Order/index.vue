<script setup>
import { computed, onMounted, ref } from 'vue';
import { statusSaleOptions } from '@/utils/dropdownOptions.js';
import { useToast } from 'primevue/usetoast';
import { formatErrorMessages } from '@/utils/helper.js';
import Loading from '@/components/Loading.vue';
import { useProductStore } from '@/stores/product';
import { useRouter } from 'vue-router';

const listOrder = ref([]);

for (let i = 1; i <= 100; i++) {
    listOrder.value.push({
        row_num: i,
        order_name: `Cửa hàng ${String.fromCharCode(64 + i)}`,
        customer_name: `Khách hàng ${String.fromCharCode(64 + i)}`,
        total_price: i * 100000,
        product_id: i
    });
}

onMounted(() => {
});
</script>

<template>
    <div>
        <div class="card flex flex-col gap-4">
            <div class="font-semibold text-xl">Tìm kiếm</div>
            <div class="flex flex-wrap gap-4">
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="name">Tên cửa hàng</label>
                    <InputText type="text" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="font-semibold">Tên khách hàng</div>
                    <InputText type="text" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="flex flex-wrap items-start gap-2">
                        <div class="field flex flex-col grow basis-0 gap-2">
                            <label for="firstname1">Giá bán từ</label>
                            <InputText id="firstname1" type="text" />
                        </div>
                        <div class="flex items-end h-full">
                            <span class="text-xl">~</span>
                        </div>
                        <div class="flex flex-col grow basis-0 gap-2">
                            <label for="lastname1">Giá bán đến</label>
                            <InputText id="lastname1" type="text" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <Button label="Tìm kiếm" severity="info" @click="" />
                <Button label="Xóa tìm" severity="secondary" @click="" />
            </div>
        </div>
        <div class="card flex flex-col gap-4">
            <div class="text-right">
                {{ infoRecordTable }}
            </div>
            <DataTable
                paginator
                :value="listOrder"
                rows="20"
                total-records="100"
                page="0"
                @page="onPageChange"
                tableStyle="min-width: 50rem">
                <Column field="row_num" header="#"></Column>
                <Column field="order_name" header="Tên cửa hàng"></Column>
                <Column field="customer_name" header="Tên khách hàng"></Column>
                <Column field="total_price" header="Giá"></Column>
                <Column header="">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <Button icon="pi pi-pencil" @click="goToEditProduct(slotProps.data.product_id)" />
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
        <Loading :loading="isLoading" />
    </div>
</template>
