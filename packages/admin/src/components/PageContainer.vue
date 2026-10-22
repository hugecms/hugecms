<script setup lang="ts">
defineOptions({ name: 'PageContainer' })
</script>

<template>
  <el-card shadow="never" class="page-container">
    <div v-if="$slots.search" class="page-container__search">
      <slot name="search" />
    </div>
    <div v-if="$slots.toolbar" class="page-container__toolbar">
      <slot name="toolbar" />
    </div>
    <slot />
    <div v-if="$slots.pagination" class="page-container__pagination">
      <slot name="pagination" />
    </div>
  </el-card>
</template>

<style scoped>
.page-container__search {
  margin-bottom: 8px;
}

.page-container__toolbar {
  display: flex;
  gap: 8px;
  align-items: center;
  margin-bottom: 12px;
}

.page-container__pagination {
  display: flex;
  justify-content: flex-end;
  margin-top: 12px;
}
</style>
