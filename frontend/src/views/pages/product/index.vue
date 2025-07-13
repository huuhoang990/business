<script setup>
import { computed, onMounted, ref } from 'vue';
import { statusSaleOptions } from '@/utils/dropdownOptions.js';
import { useToast } from 'primevue/usetoast';
import { formatErrorMessages } from '@/utils/helper.js';
import Loading from '@/components/Loading.vue';
import { useProductStore } from '@/stores/product';
import { useRouter } from 'vue-router';

const productStore = useProductStore();
let formSearchProduct = ref({
    product_name: '',
    is_sales: null,
    product_price_min: null,
    product_price_max: null,
    page: 1
});
const router = useRouter()
const listProduct = ref([]);
let rowPerPage = ref(0);
let totalRecord = ref(0);
const toast = useToast();
const isLoading = ref(false);

const resetFormSearchProduct = () => {
    formSearchProduct.value.product_name = '';
    formSearchProduct.value.is_sales = '';
    formSearchProduct.value.product_price_min = '';
    formSearchProduct.value.product_price_max = null;

    fetchProductList();
};

const fetchProductList = async () => {
    isLoading.value = true;
    try {
        await productStore.fetchProducts(formSearchProduct.value);
        setProductTableFromStore();
    } catch (error) {
        const errorMsg = formatErrorMessages(error);
        toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 5000 });
    } finally {
        isLoading.value = false;
    }
};

const onPageChange = (event) => {
    formSearchProduct.value.page = event.page + 1;
    fetchProductList();
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

const getStatusName = (statusCode) => {
    return statusSaleOptions.find((s) => s.code === String(statusCode)).name;
};

const setProductTableFromStore = () => {
    rowPerPage.value = Number(productStore.pagination.rowPerPage);
    totalRecord.value = Number(productStore.pagination.totalRecords);
    listProduct.value = productStore.productsList;
};

const goToAddProduct = () => {
    router.push('product/addProduct');
};

const goToEditProduct = (productId) => {
    router.push({ name: 'editProduct', params: { productId: productId } });
};

onMounted(() => {
    if (productStore.formSearchProduct) {
        formSearchProduct.value = productStore.formSearchProduct;
    }

    if (productStore.needUpdateProductsList) {
        fetchProductList();
    } else {
        setProductTableFromStore();
    }
});
</script>

<template>
    <div>
        <div class="card flex flex-col gap-4">
            <div class="font-semibold text-xl">Tìm kiếm</div>
            <div class="flex flex-wrap gap-4">
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="name">Tên</label>
                    <InputText id="name" type="text" v-model="formSearchProduct.product_name" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="font-semibold">Trạng thái</div>
                    <Select
                        optionLabel="name"
                        optionValue="code"
                        :options="statusSaleOptions"
                        v-model="formSearchProduct.is_sales"
                        placeholder="Select" />
                </div>
                <div class="flex flex-col grow basis-0 gap-2">
                    <div class="flex flex-wrap items-start gap-2">
                        <div class="field flex flex-col grow basis-0 gap-2">
                            <label for="firstname1">Giá bán từ</label>
                            <InputText id="firstname1" type="text" v-model="formSearchProduct.product_price_min" />
                        </div>
                        <div class="flex items-end h-full">
                            <span class="text-xl">~</span>
                        </div>
                        <div class="flex flex-col grow basis-0 gap-2">
                            <label for="lastname1">Giá bán đến</label>
                            <InputText id="lastname1" type="text" v-model="formSearchProduct.product_price_max" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <Button label="Tìm kiếm" severity="info" @click="fetchProductList" />
                <Button label="Xóa tìm" severity="secondary" @click="resetFormSearchProduct" />
            </div>
        </div>
        <div class="card flex flex-col gap-4">
            <div>
                <Button
                    label="Thêm mới"
                    icon="pi pi-plus"
                    severity="info"
                    class="mr-2"
                    @click="goToAddProduct" />
            </div>
            <div class="text-right">
                {{ infoRecordTable }}
            </div>
            <DataTable
                :value="listProduct"
                :paginator="shouldShowPaginator"
                :lazy="true"
                :rows="rowPerPage"
                :total-records="totalRecord"
                :page="formSearchProduct.page - 1"
                @page="onPageChange"
                tableStyle="min-width: 50rem">
                <Column field="row_num" header="#"></Column>
                <Column field="product_name" header="Tên sản phẩm"></Column>
                <Column field="description" header="Mô tả"></Column>
                <Column field="product_price" header="Giá"></Column>
                <Column header="Tình trạng">
                    <template #body="slotProps">
                        {{ getStatusName(slotProps.data.is_sales) }}
                    </template>
                </Column>
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
