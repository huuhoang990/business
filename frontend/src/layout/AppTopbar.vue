<script setup>
import { useLayout } from '@/layout/composables/layout';
import AppConfigurator from './AppConfigurator.vue';
import { ref } from 'vue';
import AuthService from '@/service/AuthService';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Loading from '@/components/Loading.vue';

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout();
const router = useRouter();
const isLoading = ref(false);
const authStore = useAuthStore();
const logout = async () => {
    isLoading.value = true;
    try {
        await authStore.logout();
        router.push({ name: 'login' });
    } catch (error) {
        console.log(error);
    } finally {
        isLoading.value = false;
    }
};
const profileMenuItems = [
    {
        label: 'Logout',
        icon: 'pi pi-power-off',
        command: () => {
            logout();
        },
    },
];

const profileMenu = ref(null);
const profileButton = ref(null);
const toggleProfileMenu = (event) => {
    profileMenu.value.toggle(event, profileButton.value)
}
</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-logo-container">
            <button class="layout-menu-button layout-topbar-action" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button>
            <router-link to="/" class="layout-topbar-logo">
                <div>
                     <img id="logo" src="@/assets/images/logo.png" alt="Logo" />
                </div>
            </router-link>
        </div>

        <div class="layout-topbar-actions">
            <!--
            <div class="layout-config-menu">
                <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
                    <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
                </button>
                <div class="relative">
                    <button
                        v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
                        type="button"
                        class="layout-topbar-action layout-topbar-action-highlight"
                    >
                        <i class="pi pi-palette"></i>
                    </button>
                    <AppConfigurator />
                </div>
            </div>

            <button
                class="layout-topbar-menu-button layout-topbar-action"
                v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
            >
                <i class="pi pi-ellipsis-v"></i>
            </button>
            -->
            <div class="layout-topbar-menu hidden lg:block">
                <div class="layout-topbar-menu-content">
                    <!--
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-calendar"></i>
                        <span>Calendar</span>
                    </button>
                    <button type="button" class="layout-topbar-action">
                        <i class="pi pi-inbox"></i>
                        <span>Messages</span>
                    </button>
                    -->
                    <button
                        type="button"
                        class="layout-topbar-action"
                        @click="toggleProfileMenu">
                        <i class="pi pi-user"></i>
                        <span>Profile</span>
                    </button>
                    <TieredMenu
                        ref="profileMenu"
                        :model="profileMenuItems"
                        :popup="true"
                        :showDelay="0"
                        :hideDelay="0"
                    />
                </div>
            </div>
        </div>
    </div>
    <Loading :loading="isLoading" />
</template>

<style scoped>
#logo {
    width: 50px;
    height: auto;
}
</style>
