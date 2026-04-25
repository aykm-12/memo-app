<script setup lang="ts">
import TrashSvg from "@/components/svgs/TrashSvg.vue";

const props = defineProps({
    note: Object
})

const emit = defineEmits(['deleteNote'])

function handleDelete() {
    emit('deleteNote', props.note.id)
}
</script>

<template>
    <div class="listcard">
        <div class="title">{{ note.text }}</div>
        <div class="date">{{ new Date(note.created_at).toLocaleString('ja-JP') }}</div>
        <TrashSvg class="trash" @click="handleDelete" />
    </div>
</template>

<style scoped>
.listcard {
    border: 1px solid antiquewhite;
    border-radius: 10px;
    background-color: white;
    width: 100%;
    height: 100px;
    resize: none;
    margin: 0 auto 20px;
    display: flex;
    flex-direction: column; /* 縦並び */
    justify-content: center; /*上下中央*/
    align-items: stretch; /*左寄せ*/
    padding: 20px;
    gap: 10px;
    position: relative;
}

.trash{
    opacity: 0;
    color: #666666;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
}

.listcard:hover .trash {
    opacity: 1;
}

.title{

}

.date{
    color: #666666;
    font-size: 70%;
}

</style>
