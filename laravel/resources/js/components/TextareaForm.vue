<script setup lang="ts">
import {ref} from 'vue';

 const props = defineProps({
     modelValue: String
 })

const emit = defineEmits([
    'update:modelValue',
    'submit'
])
function handleInput(e) {
    const target = e.target as HTMLTextAreaElement
    emit('update:modelValue', target.value)
}

function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault() // 改行を防ぐ
        emit('submit')     // 親に「保存して」と伝える
    }
}
</script>

<template>
<div>
    <textarea :value="modelValue"
              @input="handleInput"
              @keydown="handleKeydown"
              class="textarea"
              placeholder="メモを入力してください...&#13;&#10;（Enterで保存、Shift+Enterで改行）"
    ></textarea>
</div>
</template>

<style scoped>
.textarea{
    border: 1px solid lightgray;
    border-radius: 10px;
    width: 100%;
    height: 150px;
    resize: none;
    display: flex;
    margin: 0 auto;
    padding: 10px;
}
</style>
