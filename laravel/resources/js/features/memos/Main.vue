<script setup lang="ts">
import DocumentSvg from '@/components/svgs/DocumentSvg.vue';
import PlusSvg from '@/components/svgs/PlusSvg.vue';
import TextareaForm from "@/components/TextareaForm.vue";
import SaveButton from "@/components/saveButton.vue";
import ListCard from "@/components/ListCard.vue";

import { ref, computed } from 'vue'
const text = ref('')
// 1文字以上でtrue
const isValid = computed(() => text.value.trim().length > 0)

//メモ新規保存
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

const sortedNotes = computed(() => {
    // 元の配列を破壊しないようにスプレッド構文 [...] でコピーしてからソート
    return [...notes.value].sort((a, b) => {
        // id で降順（新しい順）に並び替え
        return b.id - a.id;
    });
});

//メモ削除
async function deleteNote(id: number) {
    await fetch(`/api/notes/${id}`, {
        method: 'DELETE'
    })

    notes.value = notes.value.filter(n => n.id !== id)
}

const editingId = ref<number | null>(null)

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // 'smooth' でなめらかに、'auto' で瞬時に移動
    });
}

//メモ編集
function editNote(id: number) {
    scrollToTop();
    const note = notes.value.find(n => n.id === id)

    if (note) {
        text.value = note.text
        selectedCategory.value = note.category_id
        editingId.value = id
    }
}

//新規保存or上書き保存判定
async function handleSubmit() {
    if (editingId.value !==null) {
        await updateNote()
    } else {
        await saveNote()
    }
}

//上書き保存
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

//カテゴリー作成
const categories = ref([])
const selectedCategory = ref(null)
const newCategoryName = ref('')

onMounted(async () => {
    const res = await fetch('/api/categories')
    categories.value = await res.json()

    if (categories.value.length > 0) {
        selectedCategory.value = null
    }
})

//カテゴリー新規保存
async function addCategory() {
    const res = await fetch('/api/categories', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: newCategoryName.value
        })
    })

    const newCat = await res.json()

    console.log('保存されたデータ:', newCat)

    categories.value.push(newCat) // 一覧に追加

    console.log('現在のカテゴリー一覧:', categories.value)

    newCategoryName.value = ''    // 入力欄を空にする
    selectedCategory.value = newCat.id  // 追加したものを選択状態にする
}

//カテゴリー削除
async function deleteCategory(id: number) {

    if (!confirm('このカテゴリーを削除してもよろしいですか？')) return

    const res = await fetch(`/api/categories/${id}`, {
        method: 'DELETE'
    })

    if (res.ok) {
        // 画面上のリストからも取り除く（リアクティブに反映）
        categories.value = categories.value.filter(cat => cat.id !== id)

        // notes.value を map で走査して、該当するノートのカテゴリー情報をクリアする
        notes.value = notes.value.map(note => {
            if (note.category_id === id) {
                return {
                    ...note,
                    category_id: null, // IDをnullにする
                    category: null      // ListCardで note.category.name を見ているのでここもクリア
                }
            }
            return note
        })

        // もし削除したカテゴリーを選択中だったら、選択を解除する
        if (selectedCategory.value === id) {
            selectedCategory.value = categories.value.length > 0 ? categories.value[0].id : null
        }

    } else {
        alert('削除に失敗しました。')
    }
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
                    @click="selectedCategory = null"
                    :class="['category-badge',{ active: selectedCategory === null}]"
                >
                    なし
                </button>
                <div v-for="cat in categories"
                    :key="cat.id">
                <button
                    @click="selectedCategory = cat.id"
                    :class="['category-badge', { active: selectedCategory === cat.id }]"
                >
                    {{ cat.name }}
                <!-- 削除ボタンを押した時に「カテゴリー選択（親のクリック）」が発火しないようにする-->
                <span class="delete-btn" @click.stop="deleteCategory(cat.id)">×</span>
                </button>
                </div>
            </div>
            <div>
                <input class="new-category" v-model="newCategoryName" placeholder="新しいカテゴリー">
                <button class="add-category" @click="addCategory">保存</button>
            </div>
            <div>
                <SaveButton
                    :disabled="!isValid"
                    @click="handleSubmit"
                    aria-describedby="memo-input-description"
                    text="メモを保存"
                    type="submit"
                    class = "SaveButton"
                >
                    <PlusSvg class="SaveButtonPlus" />
                </SaveButton>
            </div>
        </div>
        <div class="list">
            <div class="list-title">
                <DocumentSvg class="logo2" />
                <p class="saved-memo">保存されたメモ</p>
                <p class="count">{{ notes.length }}件</p>
            </div>
            <div>
                <ListCard v-for="note in sortedNotes" :key="note.id" :note="note" @deleteNote="deleteNote" @editNote="editNote" />
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
    position: relative;
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

.new-category{
    padding: 6px 12px;
    border-radius: 999px;
    border: 1px solid lightgray;
    font-size: 13px;
    transition: 0.2s;
}

.add-category{
    padding: 6px 12px;
    margin-left: 6px;
    border-radius: 999px;
    border: 1px solid coral;
    font-size: 13px;
    transition: 0.2s;
}

.add-category:hover {
    background: coral;
    color: white;
    border-color: coral;
}

.delete-btn {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4d4d;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0.7;
}

.delete-btn:hover {
    opacity: 1;
}
.SaveButton{
    color: white;
}

.SaveButtonPlus{
    color: white;
}

</style>
