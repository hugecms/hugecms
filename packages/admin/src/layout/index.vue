<script setup lang="ts">
import SidebarMenu from './components/SidebarMenu.vue'
import AppHeader from './components/AppHeader.vue'

defineOptions({ name: 'AppLayout' })
</script>

<template>
  <el-container class="layout">
    <el-aside class="layout-aside" width="208px">
      <div class="layout-logo">ECBoot</div>
      <SidebarMenu />
    </el-aside>

    <el-container>
      <el-header class="layout-header" height="56px">
        <AppHeader />
      </el-header>
      <el-main class="layout-main">
        <router-view />
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
}

.layout-logo {
  height: 56px;
  font-size: 18px;
  font-weight: 600;
  line-height: 56px;
  color: #fff;
  text-align: center;
}

.layout-header {
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}

.layout-main {
  background: #f5f7fa;
}
</style>
