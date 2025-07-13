import { getListProductsByCondition } from '@/service/ProductService';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useProductStore = defineStore('product', () => {
    const productsList = ref([]);
    let formSearchProduct = ref(null);
    const pagination = ref({
        currentPage: null,
        rowPerPage: null,
        totalRecords: null
    });
    const needUpdateProductsList = ref(true);

    const fetchProducts = async (formSearch) => {
        const response = await getListProductsByCondition(formSearch);
        needUpdateProductsList.value = false;
        productsList.value = response.data.list_products;
        pagination.value.currentPage = response.data.pagination.current_page;
        pagination.value.rowPerPage = response.data.pagination.row_per_page;
        pagination.value.totalRecords = response.data.pagination.total_records;
        formSearchProduct.value = formSearch;
    };

    return {
        productsList,
        fetchProducts,
        pagination,
        needUpdateProductsList,
        formSearchProduct
    };
});
