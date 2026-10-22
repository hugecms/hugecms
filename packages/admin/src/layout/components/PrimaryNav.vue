<script setup lang="ts">
import type { Component } from 'vue'
import { Menu } from '@element-plus/icons-vue'
import type { MenuGroup } from '@/layout/menus'
import { menuIcons } from '@/layout/components/icons'

defineOptions({ name: 'PrimaryNav' })

const props = defineProps<{
  menus: MenuGroup[]
  active: string
}>()

const emit = defineEmits<{ select: [group: MenuGroup] }>()

function resolveIcon(name: string | undefined) {
  return (menuIcons as Record<string, Component>)[name ?? ''] ?? Menu
}
</script>

<template>
  <div class="primary-nav">
    <div class="primary-nav__logo">EC</div>
    <div class="primary-nav__list">
      <div
        v-for="group in props.menus"
        :key="group.path"
        class="primary-nav__item"
        :class="{ 'is-active': group.path === props.active }"
        @click="emit('select', group)"
      >
        <el-icon :size="20"><component :is="resolveIcon(group.icon)" /></el-icon>
        <span class="primary-nav__label">{{ group.title }}</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.primary-nav {
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  width: 72px;
  height: 100%;
  background: var(--app-primary-nav-bg);
}

.primary-nav__logo {
  height: 56px;
  font-size: 16px;
  font-weight: 700;
  line-height: 56px;
  color: #fff;
  text-align: center;
  letter-spacing: 1px;
}

.primary-nav__list {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
}

.primary-nav__item {
  display: flex;
  flex-direction: column;
  gap: 6px;
  align-items: center;
  justify-content: center;
  height: 60px;
  color: var(--app-primary-nav-text);
  cursor: pointer;
  transition: background 0.15s;
}

.primary-nav__item:hover {
  background: rgb(255 255 255 / 6%);
}

.primary-nav__item.is-active {
  color: var(--app-primary-nav-text-active);
  background: var(--app-primary-nav-active-bg);
}

.primary-nav__label {
  font-size: 12px;
  line-height: 1.2;
  white-space: nowrap;
}

/* 细滚动条 */
.primary-nav__list::-webkit-scrollbar {
  width: 4px;
}

.primary-nav__list::-webkit-scrollbar-thumb {
  background: rgb(255 255 255 / 20%);
  border-radius: 2px;
}

.primary-nav__list::-webkit-scrollbar-track {
  background: transparent;
}
</style>
