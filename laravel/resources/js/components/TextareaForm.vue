<script setup lang="ts">
import {ref} from 'vue';
const texts = ref('')
const emit = defineEmits(['updateText'])
function handleInput(e) {
    emit('updateText', e.target.value)
}
</script>

<template>
<div>
    <textarea @input="handleInput" class="textarea" placeholder="メモを入力してください...&#13;&#10;（Enterで保存、Shift+Enterで改行）"></textarea>
</div>
</template>

<style scoped>
.textarea{
    border: 1px solid lightgray;
    border-radius: 10px;
    margin: 10px 20px;
    width: 300px;
    height: 150px;
    resize: none;
}
</style>
