<script setup lang="ts">
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Search } from '@element-plus/icons-vue'
import { useRouter } from 'vue-router'
import router from '@/router'
import { buildMenus } from '@/layout/menus'

defineOptions({ name: 'GlobalSearch' })

interface SearchItem {
  path: string
  title: string
  group: string
}

const routerInstance = useRouter()
const visible = ref(false)
const keyword = ref('')
const activeIndex = ref(0)
const inputRef = ref<{ focus: () => void } | null>(null)

/** 扁平化菜单：模块项 + 页面项 */
const items = computed<SearchItem[]>(() => {
  const list: SearchItem[] = []
  for (const group of buildMenus(router.options.routes)) {
    list.push({ path: group.path, title: group.title, group: '模块' })
    for (const leaf of group.children) {
      list.push({ path: leaf.path, title: leaf.title, group: group.title })
    }
  }
  return list
})

const filtered = computed(() => {
  const key = keyword.value.trim().toLowerCase()
  if (!key) {
    return items.value.slice(0, 12)
  }
  return items.value
    .filter((item) => item.title.toLowerCase().includes(key) || item.group.toLowerCase().includes(key))
    .slice(0, 12)
})

function open(): void {
  visible.value = true
  keyword.value = ''
  activeIndex.value = 0
  void nextTick(() => inputRef.value?.focus())
}

function choose(item: SearchItem | undefined): void {
  if (!item) {
    return
  }
  visible.value = false
  void routerInstance.push(item.path)
}

function onKeydown(event: KeyboardEvent): void {
  if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
    event.preventDefault()
    open()
    return
  }
  if (!visible.value) {
    return
  }
  const max = filtered.value.length
  if (max === 0) {
    return
  }
  if (event.key === 'ArrowDown') {
    event.preventDefault()
    activeIndex.value = (activeIndex.value + 1) % max
  } else if (event.key === 'ArrowUp') {
    event.preventDefault()
    activeIndex.value = (activeIndex.value - 1 + max) % max
  } else if (event.key === 'Enter') {
    event.preventDefault()
    choose(filtered.value[activeIndex.value])
  }
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onUnmounted(() => window.removeEventListener('keydown', onKeydown))
</script>

<template>
  <el-button class="global-search-trigger" text @click="open">
    <el-icon><Search /></el-icon>
    <span>搜索菜单</span>
    <span class="global-search-trigger__shortcut">⌘K</span>
  </el-button>

  <el-dialog v-model="visible" width="520px" :show-close="false" append-to-body>
    <el-input
      ref="inputRef"
      v-model="keyword"
      placeholder="搜索菜单（⌘K / Ctrl+K）"
      clearable
      @input="activeIndex = 0"
    />
    <div class="global-search-results">
      <div
        v-for="(item, index) in filtered"
        :key="item.path"
        class="global-search-item"
        :class="{ 'is-active': index === activeIndex }"
        @mouseenter="activeIndex = index"
        @click="choose(item)"
      >
        <span class="global-search-item__title">{{ item.title }}</span>
        <span class="global-search-item__group">{{ item.group }}</span>
      </div>
      <el-empty v-if="filtered.length === 0" description="无匹配菜单" :image-size="60" />
    </div>
  </el-dialog>
</template>

<style scoped>
.global-search-trigger {
  gap: 6px;
  color: #606266;
}

.global-search-trigger__shortcut {
  padding: 0 4px;
  font-size: 12px;
  color: #909399;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
}

.global-search-results {
  max-height: 320px;
  margin-top: 12px;
  overflow-y: auto;
}

.global-search-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px;
  cursor: pointer;
  border-radius: 4px;
}

.global-search-item.is-active {
  background: #ecf5ff;
}

.global-search-item__title {
  font-size: 14px;
  color: #303133;
}

.global-search-item__group {
  font-size: 12px;
  color: #909399;
}
</style>
