<script setup lang="ts">
import DocumentSvg from '@/components/svgs/DocumentSvg.vue';
import PlusSvg from '@/components/svgs/PlusSvg.vue';
import TextareaForm from "@/components/TextareaForm.vue";
import Button from "@/components/Button.vue";

import { ref, computed } from 'vue'
const text = ref('')
// 1文字以上でtrue
const isValid = computed(() => text.value.trim().length > 0)
</script>

<template>
    <header class="header">
        <div class="header-inner">
            <DocumentSvg class="logo" />
            <p class="title">かんたんメモ</p>
        </div>
        <div class="subtitle">
            思いついたことをサッと記録しよう
        </div>
    </header>
    <body class="body">
        <div class="card">
            <div class="card-title">
                <PlusSvg class="plus" />
                <p class="new-memo">新しいメモ</p>
            </div>
            <div>
                <TextareaForm @updateText="text = $event" />
            </div>
            <div>
                <Button :disabled="!isValid"/>
            </div>
        </div>
    </body>
</template>

<style scoped>
.header {
    display: flex;
    flex-direction: column;  /* 縦並び */
    align-items: center;     /* 横方向中央揃え */
    justify-content: center;
    gap: 8px;                /* 上下の余白 */
    height: 120px;
}

.header-inner {
    display: flex;
    align-items: center; /* ロゴと文字を縦中央揃え */
    gap: 12px;           /* ロゴと文字の間隔 */
}

.logo {
    width: 32px;
    height: 32px;
}

.title {
    font-size: 30px;
    font-weight: bold;
    color: coral;
}

.subtitle {
    font-size: 14px;
    color: #666;
}

.body {
    background: linear-gradient(to right, floralwhite, linen);
    display: flex;
    flex-direction: column;  /* 縦並び */
    align-items: center;     /* 横方向中央揃え */
    justify-content: center;
}

.card {
    border: 1px solid antiquewhite;
    border-radius: 10px;
    background-color: white;
    margin: 20px;
}

.card-title{
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px;
}

.new-memo{
    font-weight: bold;
}


</style>
