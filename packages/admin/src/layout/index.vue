<script setup lang="ts">
import { computed, ref } from 'vue'
import SidebarMenu from './components/SidebarMenu.vue'
import AppHeader from './components/AppHeader.vue'
import TabsBar from './components/TabsBar.vue'

defineOptions({ name: 'AppLayout' })

/** 侧边栏折叠状态（header 折叠按钮切换） */
const collapsed = ref(false)
/** 内容区重建 key（header 刷新按钮递增） */
const refreshKey = ref(0)
const asideWidth = computed(() => (collapsed.value ? '64px' : '208px'))
</script>

<template>
  <el-container class="layout">
    <el-aside class="layout-aside" :width="asideWidth">
      <div class="layout-logo">{{ collapsed ? 'EC' : 'ECBoot' }}</div>
      <SidebarMenu :collapse="collapsed" />
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
}

.layout-aside {
  display: flex;
  flex-direction: column;
  background: #001529;
  transition: width 0.2s;
}

.layout-logo {
  height: 56px;
  font-size: 18px;
  font-weight: 600;
  line-height: 56px;
  color: #fff;
  text-align: center;
  white-space: nowrap;
}

.layout-header {
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}

.layout-main {
  background: #f5f7fa;
}
</style>
