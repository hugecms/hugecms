<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import RefreshButton from '@/components/RefreshButton.vue'
import type { IProductCategoryQueryRequest, IProductCategoryResponse } from '@/types/productCategory'
import {
  productCategoryDestroyApi,
  productCategorySearchApi,
  productCategoryStoreApi,
  productCategoryUpdateApi,
} from '@/api/productCategory'

defineOptions({ name: 'ProductCategoryPage' })

interface CategoryNode extends IProductCategoryResponse {
  children: CategoryNode[]
}

const loading = ref(false)
const list = ref<CategoryNode[]>([])
const treeMap = ref<Map<number, CategoryNode>>(new Map())

const dialogVisible = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const dialogForm = reactive({ parentId: 0, name: '', sort: 0, status: 1, isRecommend: 0 })

/** 全量拉取 + 前端建树（sort 升序） */
async function fetchTree(): Promise<void> {
  loading.value = true
  try {
    const query: IProductCategoryQueryRequest = {}
    const result = await productCategorySearchApi(1, 1000, query)
    const map = new Map<number, CategoryNode>()
    const rows = result.data.map((row) => ({ ...row, children: [] as CategoryNode[] }))
    rows.forEach((row) => map.set(row.id, row))
    const roots: CategoryNode[] = []
    rows.forEach((row) => {
      const parent = map.get(row.parentId)
      if (parent && row.parentId !== 0) {
        parent.children.push(row)
      } else {
        roots.push(row)
      }
    })
    const sortRec = (nodes: CategoryNode[]) => {
      nodes.sort((a, b) => a.sort - b.sort)
      nodes.forEach((n) => sortRec(n.children))
    }
    sortRec(roots)
    list.value = roots
    treeMap.value = map
  } catch {
    // 拦截器已提示错误
  } finally {
    loading.value = false
  }
}

/** 收集自身与全部后代 ID（编辑时禁选父级） */
function collectSelfAndDescendantIds(id: number): number[] {
  const ids = [id]
  const walk = (node: CategoryNode): void => {
    node.children.forEach((child) => {
      ids.push(child.id)
      walk(child)
    })
  }
  const node = treeMap.value.get(id)
  if (node) {
    walk(node)
  }
  return ids
}

/** el-tree-select 数据：顶级虚拟节点 + 实际树；编辑时禁选自身与后代 */
const parentOptions = computed(() => {
  const disabledIds = editingId.value === null ? [] : collectSelfAndDescendantIds(editingId.value)
  const decorate = (nodes: CategoryNode[]): unknown[] =>
    nodes.map((node) => ({
      value: node.id,
      label: node.name,
      disabled: disabledIds.includes(node.id),
      children: decorate(node.children),
    }))
  return [{ value: 0, label: '顶级分类', children: decorate(list.value) }]
})

function openCreate(parentId = 0): void {
  editingId.value = null
  Object.assign(dialogForm, { parentId, name: '', sort: 0, status: 1, isRecommend: 0 })
  dialogVisible.value = true
}

function openEdit(row: IProductCategoryResponse): void {
  editingId.value = row.id
  Object.assign(dialogForm, {
    parentId: row.parentId,
    name: row.name,
    sort: row.sort,
    status: row.status,
    isRecommend: row.isRecommend,
  })
  dialogVisible.value = true
}

async function handleSubmit(): Promise<void> {
  if (!dialogForm.name.trim()) {
    ElMessage.warning('请填写分类名称')
    return
  }
  submitting.value = true
  try {
    if (editingId.value === null) {
      await productCategoryStoreApi({ ...dialogForm })
      ElMessage.success('创建成功')
    } else {
      await productCategoryUpdateApi(editingId.value, { id: editingId.value, ...dialogForm })
      ElMessage.success('更新成功')
    }
    dialogVisible.value = false
    void fetchTree()
  } catch {
    // 拦截器已提示错误
  } finally {
    submitting.value = false
  }
}

/** 删除守卫：有子级拒绝（商品引用校验后端补） */
async function handleRowDelete(row: IProductCategoryResponse): Promise<void> {
  const node = treeMap.value.get(row.id)
  if (node && node.children.length > 0) {
    ElMessage.warning('存在子分类，请先删除子分类')
    return
  }
  try {
    await ElMessageBox.confirm(`确定删除分类「${row.name}」吗？`, '删除确认', {
      type: 'warning',
      confirmButtonText: '删除',
      cancelButtonText: '取消',
    })
  } catch {
    return
  }

  try {
    await productCategoryDestroyApi({ ids: [row.id] })
    ElMessage.success('删除成功')
    void fetchTree()
  } catch {
    // 拦截器已提示错误
  }
}

onMounted(() => {
  void fetchTree()
})
</script>

<template>
  <div class="page">
    <el-card shadow="never">
      <div class="toolbar">
        <el-button type="primary" @click="openCreate(0)">新增顶级分类</el-button>
        <RefreshButton class="toolbar__refresh" @click="fetchTree" />
      </div>

      <el-table
        v-loading="loading"
        :data="list"
        row-key="id"
        :tree-props="{ children: 'children' }"
        default-expand-all
      >
        <el-table-column prop="name" label="分类名称" min-width="220" />
        <el-table-column prop="sort" label="排序" width="80" />
        <el-table-column label="首页推荐" width="100">
          <template #default="{ row }">
            <el-tag v-if="row.isRecommend === 1" type="warning">推荐</el-tag>
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column label="状态" width="90">
          <template #default="{ row }">
            <el-tag :type="row.status === 1 ? 'success' : 'info'">{{ row.status === 1 ? '启用' : '禁用' }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200" fixed="right">
          <template #default="{ row }">
            <el-button link type="primary" @click="openCreate(row.id)">添加子级</el-button>
            <el-button link type="primary" @click="openEdit(row)">编辑</el-button>
            <el-button link type="danger" @click="handleRowDelete(row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <!-- 新增/编辑对话框 -->
    <el-dialog
      v-model="dialogVisible"
      :title="editingId === null ? '新增分类' : '编辑分类'"
      width="480px"
      destroy-on-close
    >
      <el-form label-width="90px">
        <el-form-item label="父级分类" required>
          <el-tree-select
            v-model="dialogForm.parentId"
            :data="parentOptions"
            check-strictly
            :render-after-expand="false"
            style="width: 100%"
          />
        </el-form-item>
        <el-form-item label="分类名称" required>
          <el-input v-model="dialogForm.name" maxlength="50" placeholder="分类名称" />
        </el-form-item>
        <el-form-item label="排序">
          <el-input-number v-model="dialogForm.sort" :min="0" />
        </el-form-item>
        <el-form-item label="首页推荐">
          <el-switch v-model="dialogForm.isRecommend" :active-value="1" :inactive-value="0" />
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
.toolbar {
  display: flex;
  gap: 8px;
  align-items: center;
  margin-bottom: 12px;
}

/* 刷新为辅助操作：最右侧、icon 弱化 */
.toolbar__refresh {
  margin-left: auto;
}
</style>
