<script setup lang="ts">
import PlusSvg from '@/components/svgs/PlusSvg.vue';
// const props = defineProps({
//     disabled: Boolean
// })

type Props = {
    ariaDescribedBy?: string;
    ariaLabel?: string;
    disabled?: boolean;
    text?: string;
    type?: "button" | "submit" | "reset";
};

const { disabled = false, text = "保存" } = defineProps<Props>();

</script>

<template>
    <button
        :disabled="disabled"
        class="button"
        :aria-label="ariaLabel || (disabled ? `${text}できません（入力が必要です）` : text)"
        :aria-describedby="ariaDescribedBy"
        :type="type"
        role="button"
    >
        <slot />
        {{ text }}
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

</style>
