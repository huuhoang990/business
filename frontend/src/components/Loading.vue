<script setup>
import { defineProps } from 'vue';

defineProps({
  loading: Boolean,
});
</script>

<template>
    <div v-if="loading" class="overlay-loading">
        <ProgressSpinner />
    </div>
</template>
<style scoped>
.overlay-loading {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.3);
    z-index: 9999;
}
</style>
