<script setup lang="ts">
import { computed, ref } from 'vue'
import type { Component } from 'vue'
import { useRoute } from 'vue-router'
import { Menu, Search } from '@element-plus/icons-vue'
import router from '@/router'
import { buildMenus } from '@/layout/menus'
import type { MenuGroup } from '@/layout/menus'
import { menuIcons } from '@/layout/components/icons'

defineOptions({ name: 'SidebarMenu' })

const props = defineProps<{ collapse?: boolean }>()

const route = useRoute()
const filterKeyword = ref('')

/** 路由表派生两级菜单（hidden 过滤在 buildMenus 内） */
const menus = computed(() => buildMenus(router.options.routes))

/** 关键词过滤：组标题命中保留整组；否则保留命中的叶子 */
const filteredMenus = computed<MenuGroup[]>(() => {
  const key = filterKeyword.value.trim()
  if (!key) {
    return menus.value
  }
  return menus.value
    .map((group) => ({
      ...group,
      children: group.title.includes(key)
        ? group.children
        : group.children.filter((leaf) => leaf.title.includes(key)),
    }))
    .filter((group) => group.title.includes(key) || group.children.length > 0)
})

function resolveIcon(name: string | undefined) {
  return (menuIcons as Record<string, Component>)[name ?? ''] ?? Menu
}
</script>

<template>
  <div class="sidebar">
    <div v-if="!props.collapse" class="sidebar-search">
      <el-input
        v-model="filterKeyword"
        size="small"
        placeholder="搜索菜单"
        clearable
        :prefix-icon="Search"
      />
    </div>

    <el-menu
      class="sidebar-menu"
      router
      :default-active="route.path"
      :collapse="props.collapse"
      :collapse-transition="false"
    >
      <template v-for="group in filteredMenus" :key="group.path">
        <!-- 无子级：直挂叶子（如仪表盘） -->
        <el-menu-item v-if="group.children.length === 0" :index="group.path">
          <el-icon><component :is="resolveIcon(group.icon)" /></el-icon>
          <template #title><span>{{ group.title }}</span></template>
        </el-menu-item>
        <!-- 有子级：可展开菜单组 -->
        <el-sub-menu v-else :index="group.path">
          <template #title>
            <el-icon><component :is="resolveIcon(group.icon)" /></el-icon>
            <span>{{ group.title }}</span>
          </template>
          <el-menu-item v-for="leaf in group.children" :key="leaf.path" :index="leaf.path">
            <span>{{ leaf.title }}</span>
          </el-menu-item>
        </el-sub-menu>
      </template>
    </el-menu>
  </div>
</template>

<style scoped>
.sidebar {
  display: flex;
  flex: 1;
  flex-direction: column;
  min-height: 0;
}

.sidebar-search {
  padding: 8px 12px;
}

.sidebar-menu {
  flex: 1;
  overflow-y: auto;
  border-right: none;
  --el-menu-bg-color: transparent;
  --el-menu-text-color: rgb(255 255 255 / 65%);
  --el-menu-hover-bg-color: rgb(255 255 255 / 8%);
}
</style>
