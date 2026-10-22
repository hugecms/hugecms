<script setup lang="ts">
defineOptions({ name: 'ErrorState' })

withDefaults(
  defineProps<{
    description?: string
  }>(),
  { description: '数据加载失败，请稍后重试' },
)

const emit = defineEmits<{ retry: [] }>()
</script>

<template>
  <div class="error-state">
    <el-empty :description="description" :image-size="80">
      <el-button type="primary" @click="emit('retry')">重试</el-button>
    </el-empty>
  </div>
</template>

<style scoped>
.error-state {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 240px;
}
</style>
