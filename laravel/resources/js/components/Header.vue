<script setup lang="ts">
import DocumentSvg from '@/components/svgs/DocumentSvg.vue'
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
</template>

<style scoped>
.header {
    display: flex;
    flex-direction: column;  /* ← これが重要（縦並び） */
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

</style>
