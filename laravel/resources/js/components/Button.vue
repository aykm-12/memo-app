<script setup lang="ts">
import PlusSvg from '@/components/svgs/PlusSvg.vue';
const props = defineProps({
    disabled: Boolean
})
</script>

<template>
    <button :disabled="disabled" class="button">
        <PlusSvg class="plus" />
        <p class="save">メモを保存</p>
    </button>
</template>

<style scoped>
.button{
    border: 1px none;
    background: linear-gradient(to right, orange, lightsalmon);
    border-radius: 10px;
    margin: 10px 20px;
    width: 300px;
    height: 30px;
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.button:not(:disabled) {
    background: linear-gradient(to right, red, orange);
}

.plus{
    color: white;
}

.save{
    color: white;

}
</style>
