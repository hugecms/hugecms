<script setup lang="ts">
import type { Component } from 'vue'
import { Odometer } from '@element-plus/icons-vue'
import { useRoute } from 'vue-router'

defineOptions({ name: 'SidebarMenu' })

interface MenuItem {
  path: string
  title: string
  icon: Component
}

const route = useRoute()

const menuItems: MenuItem[] = [{ path: '/dashboard', title: '仪表盘', icon: Odometer }]
</script>

<template>
  <el-menu class="sidebar-menu" router :default-active="route.path">
    <el-menu-item v-for="item in menuItems" :key="item.path" :index="item.path">
      <el-icon><component :is="item.icon" /></el-icon>
      <span>{{ item.title }}</span>
    </el-menu-item>
  </el-menu>
</template>

<style scoped>
.sidebar-menu {
  flex: 1;
  border-right: none;
  --el-menu-bg-color: transparent;
  --el-menu-text-color: rgb(255 255 255 / 65%);
  --el-menu-hover-bg-color: rgb(255 255 255 / 8%);
}
</style>
