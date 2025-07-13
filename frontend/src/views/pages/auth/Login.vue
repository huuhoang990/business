<script setup>
import FloatingConfigurator from '@/components/FloatingConfigurator.vue';
import { useAuthStore } from '@/stores/auth';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import Loading from '@/components/Loading.vue';
import * as yup from 'yup';
import { getCsrfCookie } from '@/service/AuthService';
import { formatErrorMessages } from '@/utils/helper.js';

const router = useRouter();
const loginForm = ref({
    email: '',
    password: '',
    remember: false
});
const errors = ref({
    email: '',
    password: ''
});
const authStore = useAuthStore();
const isLoading = ref(false);
const schema = yup.object({
    email: yup.string().required('Email không được trống').email('Email không đúng định dạng'),
    password: yup.string().required('Mật khẩu không được trống.').min(6, 'Mật khẩu phải có ít nhất 6 ký tự.'),
    remember: yup.boolean().optional()
});
const responseError = ref('');

const handleSubmit = async () => {
    errors.value.email = '';
    errors.value.password = '';
    try {
        await schema.validate(loginForm.value, { abortEarly: false });
        await handleLogin();
    } catch (err) {
        err.inner.forEach((e) => {
            errors.value[e.path] = e.message;
        });
    }
};

const handleLogin = async () => {
    responseError.value = '';
    isLoading.value = true;
    try {
        await authStore.login(loginForm.value);
        router.push({ name: 'product' });
    } catch (error) {
        responseError.value = error.response?.data?.message;
    } finally {
        isLoading.value = false;
    }
};

onMounted(async () => {
    isLoading.value = true;
    try {
        await getCsrfCookie();
    } catch (error) {
        const errorMsg = formatErrorMessages(error);
        responseError.value = errorMsg;
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <FloatingConfigurator />
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="border-radius: 53px">
                    <div class="text-center mb-8">
                        <div class="py-10">
                            <img id="logo" src="@/assets/images/logo.png" />
                        </div>
                        <!--<span class="text-muted-color font-medium">Sign in to continue</span>-->
                    </div>

                    <div>
                        <label for="email1" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Email address" class="w-full md:w-[30rem] mb-4" v-model="loginForm.email" />
                        <Message v-if="errors.email" severity="error" variant="simple" size="small">{{ errors.email }}</Message>
                        <label for="password1" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Password</label>
                        <Password id="password1" v-model="loginForm.password" placeholder="Password" :toggleMask="true" class="mb-4" fluid :feedback="false"></Password>
                        <Message v-if="errors.password" severity="error" variant="simple" size="small">{{ errors.password }}</Message>
                        <div class="flex items-center justify-between mt-2 mb-5 gap-8">
                            <div class="flex items-center">
                                <Checkbox v-model="loginForm.remember" id="rememberme1" binary class="mr-2"></Checkbox>
                                <label for="rememberme1">Remember me</label>
                            </div>
                            <!--<span class="font-medium no-underline ml-2 text-right cursor-pointer text-primary">Forgot password?</span>-->
                        </div>
                        <Message v-if="responseError" class="mb-5" severity="error" variant="simple" size="small">{{ responseError }}</Message>
                        <Button label="Sign In" class="w-full" @click="handleSubmit"></Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Loading :loading="isLoading" />
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}

#logo {
    width: 225px;
    height: auto;
    margin: 0 auto;
    background-color: rgba(42, 49, 60, 1);
}
</style>
