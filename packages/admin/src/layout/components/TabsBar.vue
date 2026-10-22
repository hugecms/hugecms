<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTabsStore } from '@/stores/tabs'

defineOptions({ name: 'TabsBar' })

const route = useRoute()
const router = useRouter()
const tabsStore = useTabsStore()

const barRef = ref<HTMLElement | null>(null)
const activePath = computed(() => route.path)

/** 当前标签滚入视野（标签多时保证可见） */
function scrollActiveIntoView(): void {
  void nextTick(() => {
    barRef.value
      ?.querySelector('.tabs-bar__item.is-active')
      ?.scrollIntoView({ inline: 'nearest', block: 'nearest' })
  })
}

watch(
  () => route.path,
  () => {
    if (route.meta.title) {
      tabsStore.addTab(route.path, String(route.meta.title))
    }
    scrollActiveIntoView()
  },
  { immediate: true },
)

/** 鼠标滚轮纵向增量转为横向滚动（标签栏不显示滚动条） */
function handleWheel(event: WheelEvent): void {
  const el = barRef.value
  if (!el || Math.abs(event.deltaY) <= Math.abs(event.deltaX)) {
    return
  }
  el.scrollLeft += event.deltaY
  event.preventDefault()
}

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

function handleContextCommand(command: string, path: string): void {
  if (command === 'close') {
    handleClose(path)
    return
  }
  if (command === 'others') {
    tabsStore.closeOthers(path)
    if (route.path !== path) {
      void router.push(path)
    }
    return
  }
  if (command === 'right') {
    tabsStore.closeRight(path)
    if (!tabsStore.tabs.some((tab) => tab.path === route.path)) {
      void router.push(path)
    }
    return
  }
  if (command === 'all') {
    tabsStore.closeAll()
    void router.push('/dashboard')
  }
}
</script>

<template>
  <div ref="barRef" class="tabs-bar" @wheel="handleWheel">
    <el-dropdown
      v-for="tab in tabsStore.tabs"
      :key="tab.path"
      trigger="contextmenu"
      @command="(command: string) => handleContextCommand(command, tab.path)"
    >
      <el-tag
        class="tabs-bar__item"
        :class="{ 'is-active': tab.path === activePath }"
        :effect="tab.path === activePath ? 'dark' : 'plain'"
        :closable="tab.closable"
        @click="handleSelect(tab.path)"
        @close="handleClose(tab.path)"
      >
        {{ tab.title }}
      </el-tag>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item command="close" :disabled="!tab.closable">关闭</el-dropdown-item>
          <el-dropdown-item command="others">关闭其他</el-dropdown-item>
          <el-dropdown-item command="right">关闭右侧</el-dropdown-item>
          <el-dropdown-item command="all" divided>全部关闭</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
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
  border-bottom: 1px solid var(--app-border);
  /* 隐藏原生横向滚动条，改用滚轮横向滚动 */
  scrollbar-width: none;
}

.tabs-bar::-webkit-scrollbar {
  display: none;
}

.tabs-bar__item {
  flex-shrink: 0;
  max-width: 140px;
  cursor: pointer;
  user-select: none;
}

.tabs-bar__item :deep(.el-tag__content) {
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>
