<script setup lang="ts">
import DocumentSvg from '@/components/svgs/DocumentSvg.vue';
import PlusSvg from '@/components/svgs/PlusSvg.vue';
import TextareaForm from "@/components/TextareaForm.vue";
import Button from "@/components/Button.vue";
import ListCard from "@/components/svgs/ListCard.vue";

import { ref, computed } from 'vue'
const text = ref('')
// 1文字以上でtrue
const isValid = computed(() => text.value.trim().length > 0)

const categories = ref([])
const selectedCategory = ref(null)

onMounted(async () => {
    const res = await fetch('/api/categories')
    categories.value = await res.json()

    if (categories.value.length > 0) {
        selectedCategory.value = categories.value[0].id
    }
})

async function saveNote() {
    const res = await fetch('/api/notes', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            text: text.value,
            category_id: selectedCategory.value
        })
    })

    const data = await res.json()
    console.log(data)

    notes.value.unshift(data)

    text.value = ''
}

//note配列
const notes =ref([])

import { onMounted } from 'vue'

onMounted(async () => {
    const res = await fetch('/api/notes')
    const data = await res.json()
    console.log(data)
    notes.value = data
})

async function deleteNote(id: number) {
    await fetch(`/api/notes/${id}`, {
        method: 'DELETE'
    })

    notes.value = notes.value.filter(n => n.id !== id)
}

const editingId = ref<number | null>(null)

function editNote(id: number) {
    const note = notes.value.find(n => n.id === id)
    if (note) {
        text.value = note.text
        selectedCategory.value = note.category_id
        editingId.value = id
    }
}

async function handleSubmit() {
    if (editingId.value !==null) {
        await updateNote()
    } else {
        await saveNote()
    }
}

async function updateNote() {
    if (editingId.value === null) return

    const res = await fetch(`/api/notes/${editingId.value}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            text: text.value,
            category_id: selectedCategory.value
        })
    })

    const data = await res.json()

    const index = notes.value.findIndex(n => n.id === editingId.value)
    if (index !== -1) {
        notes.value[index] = data
    }

    text.value = ''
    editingId.value = null

}

</script>

<template>
    <header class="header">
        <div class="header-inner">
            <DocumentSvg class="logo" />
            <p class="title">かんたんメモ</p>
        </div>
        <div class="subtitle">
            <p>思いついたことをサッと記録しよう</p>
        </div>
    </header>
    <body class="body">
    <div class="container">
        <div class="card">
            <div class="card-title">
                <PlusSvg class="plus" />
                <p class="new-memo">新しいメモ</p>
            </div>
            <div>
                <TextareaForm v-model="text" @submit="handleSubmit" />
            </div>
            <div class="category-wrap">
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="selectedCategory = cat.id"
                    :class="['category-badge', { active: selectedCategory === cat.id }]"
                >
                    {{ cat.name }}
                </button>
            </div>
            <div>
                <Button :disabled="!isValid" @click="handleSubmit" />
            </div>
        </div>
        <div class="list">
            <div class="list-title">
                <DocumentSvg class="logo2" />
                <p class="saved-memo">保存されたメモ</p>
                <p class="count">{{ notes.length }}件</p>
            </div>
            <div>
                <ListCard v-for="note in notes" :key="note.id" :note="note" @deleteNote="deleteNote" @editNote="editNote" />
            </div>
        </div>
        <p class="hint">💡 Enterキーで素早く保存できます</p>
    </div>
    </body>
</template>

<style scoped>
.header {
    display: flex;
    flex-direction: column;  /* 縦並び */
    align-items: center;     /* 縦方向中央揃え */
    justify-content: center;
    gap: 8px;                /* 上下の余白 */
    height: 120px;
    border-bottom: 2px solid antiquewhite;
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
    color: #666666;
}

.body {
    background: linear-gradient(to right, floralwhite, linen);
    display: flex;
}

.container{
    max-width: 600px;
    width: 90%;
    margin: 30px auto; /* 中央寄せ＆左右統一 */
}

.card {
    border: 1px solid antiquewhite;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border-radius: 10px;
    background-color: white;
    max-width: 600px;
    display: flex;
    flex-direction: column;  /* 縦並び */
    align-items: stretch; /*左寄せ*/
    padding: 30px;
    gap: 20px;
}

.card-title{
    display: flex;
    align-items: center;
    gap: 8px;
}

.new-memo{
    font-weight: bold;
}

.list{
    max-width: 600px;
    display: flex;
    flex-direction: column;  /* 縦並び */
    align-items: stretch; /*左寄せ*/
    gap: 20px;
    margin:30px 0;
}

.list-title{
    display: flex;
    align-items: center; /*左寄せ*/
    gap: 8px;
}

.count{
    margin-left: auto;
    color: #666666;
    font-size: 80%;
    padding: 5px 10px;
    background-color: antiquewhite;
    border-radius: 30px;
}

.saved-memo{
    font-weight: bold;
}

.hint{
    text-align: center;
    color: darkgray;
    font-size: 90%;
}

.category-wrap {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.category-badge {
    padding: 6px 12px;
    border-radius: 999px;
    border: 1px solid lightgray;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
}

.category-badge:hover {
    transform: translateY(-1px);
    border-color: coral;
}

.category-badge.active {
    background: coral;
    color: white;
    border-color: coral;
}
</style>
