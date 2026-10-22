<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { useMediaQuery } from '@vueuse/core'
import { useRoute } from 'vue-router'
import router from '@/router'
import { buildMenus } from '@/layout/menus'
import type { MenuGroup } from '@/layout/menus'
import AppHeader from './components/AppHeader.vue'
import PrimaryNav from './components/PrimaryNav.vue'
import SecondaryNav from './components/SecondaryNav.vue'
import TabsBar from './components/TabsBar.vue'

defineOptions({ name: 'AppLayout' })

const route = useRoute()

/** 折叠：隐藏二级导航（一级 icon 栏常驻） */
const collapsed = ref(false)
/** 窄屏（<1440）自动收起二级导航，给内容区让出宽度；用户仍可手动展开 */
const isNarrow = useMediaQuery('(max-width: 1439px)')
watch(
  isNarrow,
  (narrow) => {
    if (narrow) {
      collapsed.value = true
    }
  },
  { immediate: true },
)
/** 内容区重建 key（header 刷新按钮递增） */
const refreshKey = ref(0)

/** 一级组 + 二级页面（路由表派生） */
const menus = computed(() => buildMenus(router.options.routes))

/** 当前一级组：由当前路由反查（直接访问二级路径也能正确定位） */
const activeGroupPath = ref('')
watch(
  () => route.path,
  (path) => {
    const group = menus.value.find(
      (item) => item.path === path || item.children.some((leaf) => leaf.path === path),
    )
    if (group) {
      activeGroupPath.value = group.path
    }
  },
  { immediate: true },
)

const activeGroup = computed(() => menus.value.find((group) => group.path === activeGroupPath.value) ?? null)

/** 是否显示二级导航：折叠时不显示；当前一级组无二级（如仪表盘）也不显示 */
const hasSecondaryNav = computed(
  () => !collapsed.value && activeGroup.value !== null && activeGroup.value.children.length > 0,
)

const asideWidth = computed(() => (hasSecondaryNav.value ? '252px' : '72px'))

/** 点击一级：切换二级导航；当前路由不属于该组时进入组内第一页 */
function handlePrimarySelect(group: MenuGroup): void {
  activeGroupPath.value = group.path
  if (group.children.length === 0) {
    void router.push(group.path)
    return
  }
  if (!group.children.some((leaf) => leaf.path === route.path)) {
    void router.push(group.children[0]!.path)
  }
}
</script>

<template>
  <el-container class="layout">
    <el-aside class="layout-aside" :width="asideWidth">
      <div class="layout-nav">
        <PrimaryNav :menus="menus" :active="activeGroupPath" @select="handlePrimarySelect" />
        <SecondaryNav
          v-if="hasSecondaryNav"
          :group="activeGroup!"
          :active-path="route.path"
        />
      </div>
    </el-aside>

    <el-container>
      <el-header class="layout-header" height="56px">
        <AppHeader v-model:collapsed="collapsed" @refresh="refreshKey++" />
      </el-header>
      <TabsBar />
      <el-main class="layout-main">
        <router-view :key="refreshKey" />
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped>
.layout {
  height: 100%;
  /* 后台最小工作宽度：低于此宽度整体横向滚动，避免表格被挤压 */
  min-width: 1280px;
}

.layout-aside {
  padding: 0;
  background: var(--app-primary-nav-bg);
  transition: width 0.2s;
}

.layout-nav {
  display: flex;
  height: 100%;
}

.layout-header {
  background: #fff;
  border-bottom: 1px solid var(--app-border);
}

.layout-main {
  overflow-x: auto;
  background: var(--app-content-bg);
}
</style>
