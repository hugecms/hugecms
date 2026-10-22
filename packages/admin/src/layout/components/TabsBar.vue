<script setup lang="ts">
import { computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTabsStore } from '@/stores/tabs'

defineOptions({ name: 'TabsBar' })

const route = useRoute()
const router = useRouter()
const tabsStore = useTabsStore()

const activePath = computed(() => route.path)

watch(
  () => route.path,
  () => {
    if (route.meta.title) {
      tabsStore.addTab(route.path, String(route.meta.title))
    }
  },
  { immediate: true },
)

function handleSelect(path: string): void {
  if (path !== route.path) {
    void router.push(path)
  }
}

function handleClose(path: string): void {
  const next = tabsStore.closeTab(path)
  if (path === route.path) {
    void router.push(next)
  }
}
</script>

<template>
  <div class="tabs-bar">
    <el-tag
      v-for="tab in tabsStore.tabs"
      :key="tab.path"
      class="tabs-bar__item"
      :effect="tab.path === activePath ? 'dark' : 'plain'"
      :closable="tab.closable"
      @click="handleSelect(tab.path)"
      @close="handleClose(tab.path)"
    >
      {{ tab.title }}
    </el-tag>
  </div>
</template>

<style scoped>
.tabs-bar {
  display: flex;
  gap: 8px;
  align-items: center;
  height: 40px;
  padding: 0 16px;
  overflow-x: auto;
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}

.tabs-bar__item {
  flex-shrink: 0;
  cursor: pointer;
  user-select: none;
}
</style>
