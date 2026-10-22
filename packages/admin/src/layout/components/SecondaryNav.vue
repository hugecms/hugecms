<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Search } from '@element-plus/icons-vue'
import type { MenuGroup } from '@/layout/menus'

defineOptions({ name: 'SecondaryNav' })

const props = defineProps<{
  group: MenuGroup
  activePath: string
}>()

const router = useRouter()
const keyword = ref('')

const filteredLeaves = computed(() => {
  const key = keyword.value.trim()
  if (!key) {
    return props.group.children
  }
  return props.group.children.filter((leaf) => leaf.title.includes(key))
})

function go(path: string): void {
  if (path !== props.activePath) {
    void router.push(path)
  }
}
</script>

<template>
  <div class="secondary-nav">
    <div class="secondary-nav__header">{{ props.group.title }}</div>
    <div class="secondary-nav__search">
      <el-input v-model="keyword" size="small" placeholder="搜索页面" clearable :prefix-icon="Search" />
    </div>
    <div class="secondary-nav__list">
      <div
        v-for="leaf in filteredLeaves"
        :key="leaf.path"
        class="secondary-nav__item"
        :class="{ 'is-active': leaf.path === props.activePath }"
        @click="go(leaf.path)"
      >
        {{ leaf.title }}
      </div>
      <el-empty v-if="filteredLeaves.length === 0" description="无匹配页面" :image-size="48" />
    </div>
  </div>
</template>

<style scoped>
.secondary-nav {
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  width: 180px;
  height: 100%;
  background: var(--app-secondary-nav-bg);
  border-right: 1px solid var(--app-border);
}

.secondary-nav__header {
  height: 56px;
  padding: 0 16px;
  font-size: 14px;
  font-weight: 600;
  line-height: 56px;
  color: #1d2129;
  border-bottom: 1px solid var(--app-border);
}

.secondary-nav__search {
  padding: 8px 10px;
}

.secondary-nav__list {
  flex: 1;
  min-height: 0;
  padding: 0 8px;
  overflow-y: auto;
}

.secondary-nav__item {
  padding: 9px 12px;
  margin-bottom: 2px;
  font-size: 13px;
  color: var(--app-secondary-nav-text);
  cursor: pointer;
  border-radius: 4px;
}

.secondary-nav__item:hover {
  background: #f5f7fa;
}

.secondary-nav__item.is-active {
  font-weight: 500;
  color: var(--el-color-primary);
  background: var(--app-secondary-nav-active-bg);
}

/* 细滚动条 */
.secondary-nav__list::-webkit-scrollbar {
  width: 4px;
}

.secondary-nav__list::-webkit-scrollbar-thumb {
  background: rgb(0 0 0 / 12%);
  border-radius: 2px;
}

.secondary-nav__list::-webkit-scrollbar-track {
  background: transparent;
}
</style>
