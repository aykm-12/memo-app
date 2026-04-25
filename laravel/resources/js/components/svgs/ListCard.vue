<script setup lang="ts">
const props = defineProps({
    note: Object
})
</script>

<template>
    <div class="listcard">
        <div class="title">{{ note.text }}</div>
        <div class="date">{{ note.created_at }}</div>
    </div>
</template>

<style scoped>
.listcard{
    border: 1px solid antiquewhite;
    border-radius: 10px;
    background-color: white;
    width: 100%;
    height: 100px;
    resize: none;
    margin: 0 auto 20px;
    display: flex;
    flex-direction: column;  /* 縦並び */
    justify-content: center; /*上下中央*/
    align-items: stretch; /*左寄せ*/
    padding: 20px;
    gap: 10px;
}

.title{

}

.date{
    color: #666666;
    font-size: 60%;
}


</style>
