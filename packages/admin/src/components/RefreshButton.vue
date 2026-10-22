<script setup lang="ts">
import { Refresh } from '@element-plus/icons-vue'

defineOptions({ name: 'RefreshButton', inheritAttrs: false })

const emit = defineEmits<{ click: [] }>()
</script>

<template>
  <el-tooltip content="刷新" placement="top">
    <el-button v-bind="$attrs" text circle @click="emit('click')">
      <el-icon><Refresh /></el-icon>
    </el-button>
  </el-tooltip>
</template>
