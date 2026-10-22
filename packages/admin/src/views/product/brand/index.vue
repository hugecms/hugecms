<script setup lang="ts">
import { reactive, ref } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import type { IProductBrandQueryRequest, IProductBrandResponse } from '@/types/productBrand'
import {
  productBrandDestroyApi,
  productBrandSearchApi,
  productBrandStoreApi,
  productBrandUpdateApi,
} from '@/api/productBrand'
import { useCrudTable } from '@/composables/useCrudTable'
import { exportCsv } from '@/utils/export'

defineOptions({ name: 'ProductBrandPage' })

const dialogVisible = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const dialogForm = reactive({ name: '', logo: '', sort: 0, status: 1 })

const { loading, list, total, page, pageSize, searchForm, selectedRows, selectedIds, handleSearch, handleReset, handleSizeChange, handleCurrentChange, handleSelectionChange, handleDelete } = useCrudTable<
  IProductBrandQueryRequest,
  IProductBrandResponse
>({
  searchApi: productBrandSearchApi,
  destroyApi: productBrandDestroyApi,
  createSearchForm: () => ({ name: '' }),
})

function openCreate(): void {
  editingId.value = null
  Object.assign(dialogForm, { name: '', logo: '', sort: 0, status: 1 })
  dialogVisible.value = true
}

function openEdit(row: IProductBrandResponse): void {
  editingId.value = row.id
  Object.assign(dialogForm, { name: row.name, logo: row.logo, sort: row.sort, status: row.status })
  dialogVisible.value = true
}

async function handleSubmit(): Promise<void> {
  if (!dialogForm.name.trim()) {
    ElMessage.warning('请填写品牌名称')
    return
  }
  submitting.value = true
  try {
    if (editingId.value === null) {
      await productBrandStoreApi({ ...dialogForm })
      ElMessage.success('创建成功')
    } else {
      await productBrandUpdateApi(editingId.value, { id: editingId.value, ...dialogForm })
      ElMessage.success('更新成功')
    }
    dialogVisible.value = false
    handleSearch()
  } catch {
    // 拦截器已提示错误
  } finally {
    submitting.value = false
  }
}

function handleRowDelete(row: IProductBrandResponse): void {
  void handleDelete([row.id])
}

function confirmBatchDelete(): void {
  void handleDelete(selectedIds.value)
}

/** 导出当前页数据（全量导出待后端导出接口） */
function handleExport(): void {
  exportCsv('商品品牌', [
    { key: 'name', label: '品牌名称' },
    { key: 'logo', label: 'LOGO' },
    { key: 'sort', label: '排序' },
    { key: 'status', label: '状态' },
    { key: 'createdAt', label: '创建时间' },
  ], list.value)
}
</script>

<template>
  <div class="page">
    <el-card shadow="never">
      <!-- 搜索行 -->
      <el-form inline class="search-form" @submit.prevent>
        <el-form-item label="品牌名称">
          <el-input
            v-model="searchForm.name"
            placeholder="品牌名称"
            clearable
            style="width: 200px"
            @keyup.enter="handleSearch"
          />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="handleSearch">查询</el-button>
          <el-button @click="handleReset">重置</el-button>
        </el-form-item>
      </el-form>

      <!-- 工具栏 -->
      <div class="toolbar">
        <el-button type="primary" @click="openCreate">新增品牌</el-button>
        <el-button type="danger" plain :disabled="selectedIds.length === 0" @click="confirmBatchDelete">
          批量删除
        </el-button>
        <el-button @click="handleExport">导出当前页</el-button>
      </div>

      <!-- 列表 -->
      <el-table v-loading="loading" :data="list" @selection-change="handleSelectionChange">
        <el-table-column type="selection" width="48" />
        <el-table-column prop="id" label="ID" width="80" />
        <el-table-column prop="name" label="品牌名称" min-width="160" />
        <el-table-column label="LOGO" width="90">
          <template #default="{ row }">
            <el-image
              v-if="row.logo"
              :src="row.logo"
              :preview-src-list="[row.logo]"
              preview-teleported
              fit="cover"
              style="width: 40px; height: 40px; border-radius: 4px"
            />
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column prop="sort" label="排序" width="80" />
        <el-table-column label="状态" width="90">
          <template #default="{ row }">
            <el-tag :type="row.status === 1 ? 'success' : 'info'">{{ row.status === 1 ? '启用' : '禁用' }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="createdAt" label="创建时间" width="180" />
        <el-table-column label="操作" width="140" fixed="right">
          <template #default="{ row }">
            <el-button link type="primary" @click="openEdit(row)">编辑</el-button>
            <el-button link type="danger" @click="handleRowDelete(row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页 -->
      <el-pagination
        class="pagination"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
        :current-page="page"
        :page-size="pageSize"
        :page-sizes="[10, 20, 50]"
        @current-change="handleCurrentChange"
        @size-change="handleSizeChange"
      />
    </el-card>

    <!-- 新增/编辑对话框 -->
    <el-dialog
      v-model="dialogVisible"
      :title="editingId === null ? '新增品牌' : '编辑品牌'"
      width="480px"
      destroy-on-close
    >
      <el-form label-width="90px">
        <el-form-item label="品牌名称" required>
          <el-input v-model="dialogForm.name" maxlength="50" placeholder="品牌名称" />
        </el-form-item>
        <el-form-item label="LOGO 地址">
          <el-input v-model="dialogForm.logo" placeholder="图片 URL（上传组件待接入附件管理）" />
        </el-form-item>
        <el-form-item label="排序">
          <el-input-number v-model="dialogForm.sort" :min="0" />
        </el-form-item>
        <el-form-item label="状态">
          <el-switch v-model="dialogForm.status" :active-value="1" :inactive-value="0" active-text="启用" />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" :loading="submitting" @click="handleSubmit">确定</el-button>
      </template>
    </el-dialog>
  </div>
</template>

<style scoped>
.search-form {
  margin-bottom: 4px;
}

.toolbar {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}

.pagination {
  display: flex;
  justify-content: flex-end;
  margin-top: 12px;
}
</style>
