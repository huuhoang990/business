<script setup>
import { onMounted, ref } from 'vue';
import { statusSaleOptions } from '@/utils/dropdownOptions.js';
import defaultImage from '@/assets/images/default_image.png';
import * as yup from 'yup';
import { getDetailProduct, editProduct } from '@/service/ProductService.js';
import { formatErrorMessages } from '@/utils/helper.js';
import { useRouter } from 'vue-router';
import { useProductStore } from '@/stores/product';
import Loading from '@/components/Loading.vue';

const productForm = ref({
    product_id: null,
    product_name: '',
    upload_image: null,
    product_price: '',
    description: '',
    is_sales: null,
    delete_old_image: 0
});
const productFormErrors = ref({
    product_name: '',
    upload_image: '',
    product_price: '',
    description: '',
    is_sales: ''
});
const imagePreviewUrl = ref(defaultImage);
const uploadedFile = ref();
const resetUploadFile = () => {
    uploadedFile.value.upload();
    productForm.value.upload_image = null;
    imagePreviewUrl.value = defaultImage;
    productForm.value.delete_old_image = 1;
};
const maxImgSize = 2 * 1024 * 1024;
const maxImgWidth = 1024;
const maxImgHeight = 1024;
const isLoading = ref(false);
const responseError = ref('');
const router = useRouter();
const productStore = useProductStore();

const onFileSelect = (event) => {
    const file = event.files[0];
    productForm.value.upload_image = file;
    imagePreviewUrl.value = URL.createObjectURL(file);
    productForm.value.delete_old_image = 1;
};

const schema = yup.object().shape({
    product_name: yup.string().required('Tên sản phẩm là bắt buộc').max(255, 'Tên sản phẩm tối đa 255 ký tự'),
    upload_image: yup
        .mixed()
        .nullable()
        .notRequired()
        .test('fileType', 'Chỉ cho upload các file hình png, jpg, jpeg', (value) => {
            if (!value) return true;
            const acceptedTypes = ['image/jpeg', 'image/png'];
            return acceptedTypes.includes(value.type);
        })
        .test('imageDimensions', 'Kích thước không quá 1024px', (value) => {
            if (!value) return true;
            return new Promise((resolve) => {
                const img = new Image();
                const url = URL.createObjectURL(value);
                img.onload = () => {
                    const result = img.width <= maxImgWidth && img.height <= maxImgHeight;
                    URL.revokeObjectURL(url);
                    resolve(result);
                };
                img.onerror = () => {
                    URL.revokeObjectURL(url);
                    resolve(false);
                };
                img.src = url;
            });
        })
        .test('fileSize', 'Kích thước hình ảnh tối đa 2MB', (value) => {
            if (!value) return true;
            return value.size <= maxImgSize;
        }),
    product_price: yup.number()
        .typeError('Giá sản phẩm phải là một số')
        .required('Giá sản phẩm là bắt buộc')
        .min(0, 'Giá sản phẩm không thể âm'),
    description: yup.string().nullable(),
    is_sales: yup.boolean().required('Trạng thái sản phẩm là bắt buộc'),
});

const handleSubmit = async () => {
    productFormErrors.value = {
        product_name: '',
        upload_image: '',
        product_price: '',
        description: '',
        is_sales: null
    };
    responseError.value = '';
    try {
        await schema.validate(productForm.value, { abortEarly: false });
        handleAddProduct();
    } catch (err) {
        err.inner.forEach((e) => {
            productFormErrors.value[e.path] = e.message;
        });
    }
};

const handleAddProduct = async () => {
    const data = new FormData();
    data.append('product_id', productForm.value.product_id);
    data.append('product_name', productForm.value.product_name);
    console.log('productForm.value.upload_image', productForm.value);
    if (productForm.value.upload_image) {
        data.append('upload_image', productForm.value.upload_image);
    }
    data.append('product_price', productForm.value.product_price);
    data.append('description', productForm.value.description);
    data.append('is_sales', productForm.value.is_sales);
    data.append('delete_old_image', productForm.value.delete_old_image);

    isLoading.value = true;
    try {
        await editProduct(data);
        backToProductPage(true);
    } catch (error) {
        responseError.value = formatErrorMessages(error);
    } finally {
        isLoading.value = false;
    }
};

const getProduct = async (productId) => {
    isLoading.value = true;
    try {
        const response = await getDetailProduct(productId);
        productForm.value.product_id = response.data.product_id;
        productForm.value.product_name = response.data.product_name;
        productForm.value.upload_image = response.data.upload_image;
        productForm.value.product_price = response.data.product_price;
        productForm.value.description = response.data.description;
        productForm.value.is_sales = String(response.data.is_sales);
        imagePreviewUrl.value = response.data.data_image || defaultImage;
    } catch (error) {
        responseError.value = formatErrorMessages(error);
    } finally {
        isLoading.value = false;
    }
};

const backToProductPage = (isUpdatedData) => {
    if (isUpdatedData) {
        productStore.needUpdateProductsList = true;
    }
    router.push({ name: 'product' });
};

onMounted(() => {
    const productId = router.currentRoute.value.params.productId;
    if (productId) {
        getProduct(productId);
    }
});
</script>
<template>
    <div>
        <div class="font-semibold text-xl mb-4">Thêm sản phẩm</div>
        <div class="card md:w-1/2">
            <div class="flex flex-col gap-2 mb-5">
                <div class="font-semibold text-xl mb-4">Hình ảnh</div>
                <div>
                    <Image
                        v-if="imagePreviewUrl"
                        :src="imagePreviewUrl"
                        alt="Image Preview"
                        width="350"
                        height="100%"
                        class="mb-2"
                    />
                </div>
                <div class="flex items-left gap-2 mt-2">
                    <FileUpload
                        ref="uploadedFile"
                        mode="basic"
                        accept="image/*" 
                        @select="onFileSelect" 
                        customUpload />
                    <Button label="Huỷ" @click="resetUploadFile" severity="secondary" />
                </div>
                <Message v-if="productFormErrors.upload_image" class="mt-2 full-width" severity="error" variant="simple" size="small">
                    {{ productFormErrors.upload_image }}
                </Message>
            </div>
            <div class="flex flex-col gap-4">
                <div class="grid grid-cols-12 gap-2">
                    <label for="product_name" class="flex items-center col-span-12 mb-2 md:col-span-2 md:mb-0">Tên sản phẩm</label>
                    <div id="product_name" class="col-span-12 md:col-span-10">
                        <InputText v-model="productForm.product_name" class="w-full" type="text" />
                        <Message v-if="productFormErrors.product_name" class="mt-2" severity="error" variant="simple" size="small">
                            {{ productFormErrors.product_name }}
                        </Message>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <label for="product_price" class="flex items-center col-span-12 mb-2 md:col-span-2 md:mb-0">Giá bán</label>
                    <div class="col-span-12 md:col-span-10">
                        <InputText id="product_price" v-model="productForm.product_price" class="w-full" type="text" />
                        <Message v-if="productFormErrors.product_price" class="mt-2" severity="error" variant="simple" size="small">
                            {{ productFormErrors.product_price }}
                        </Message>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <label for="description" class="flex items-center col-span-12 mb-2 md:col-span-2 md:mb-0">Mô tả</label>
                    <div class="col-span-12 md:col-span-10">
                        <Textarea id="description" v-model="productForm.description" type="text" class="w-full" rows="5" />
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <label for="is_sales" class="flex items-center col-span-12 mb-2 md:col-span-2 md:mb-0">Trạng thái</label>
                    <div class="col-span-12 md:col-span-10">
                        <Select
                            id="is_sales"
                            class="w-full"
                            v-model="productForm.is_sales"
                            optionLabel="name"
                            optionValue="code"
                            :options="statusSaleOptions"
                            placeholder="Select" />
                        <Message v-if="productFormErrors.is_sales" class="mt-2" severity="error" variant="simple" size="small">
                            {{ productFormErrors.is_sales }}
                        </Message>
                    </div>
                </div>
                <div>
                    <Message v-if="responseError" class="my-3" severity="error" variant="simple" size="small">{{ responseError }}</Message>
                </div>
                <div class="mt-4">
                    <Button label="Lưu" severity="success" class="mr-2" @click="handleSubmit" />
                    <Button label="Hủy" severity="secondary" @click="backToProductPage(false)" />
                </div>
            </div>
        </div>
    </div>
    <Loading :loading="isLoading" />
</template>
