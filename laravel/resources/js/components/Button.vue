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
    background: linear-gradient(to right, coral, lightcoral);
    filter: brightness(1.5);
    border-radius: 10px;
    margin: 0 auto;
    width: 100%;
    height: 50px;
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.button:not(:disabled) {
    background: linear-gradient(to right, coral, lightcoral);
    filter: brightness(1);
}

.plus{
    color: white;
}

.save{
    color: white;

}
</style>
