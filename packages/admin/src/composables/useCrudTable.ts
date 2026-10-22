import { computed, reactive, ref, shallowRef } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
// ElMessageBox 函数式调用需显式引入样式（同 request.ts 对 message 的处理惯例）
import 'element-plus/es/components/message-box/style/css'

export interface CrudTableRow {
  id: number
}

export interface CrudTableOptions<TQuery extends object, TRow extends CrudTableRow> {
  /** 列表查询（生成的 *SearchApi：入参 page/pageSize/query，出参含 total/data） */
  searchApi: (page: number, pageSize: number, query: TQuery) => Promise<{ total: number; data: TRow[] }>
  /** 批量删除（生成的 *DestroyApi：入参 { ids }）；缺省时页面不提供删除能力 */
  destroyApi?: (data: { ids: number[] }) => Promise<unknown>
  /** 搜索表单工厂：reset 时整体重建，避免引用残留 */
  createSearchForm: () => TQuery
  defaultPageSize?: number
}

/**
 * 标准 CRUD 列表页数据逻辑：搜索/分页/多选/删除确认。
 * 错误提示由 request.ts 拦截器统一弹出（BusinessError/HTTP 分类文案），此处只做状态兜底，不重复 ElMessage。
 */
export function useCrudTable<TQuery extends object, TRow extends CrudTableRow>(
  options: CrudTableOptions<TQuery, TRow>,
) {
  const loading = ref(false)
  const list = shallowRef<TRow[]>([])
  const total = ref(0)
  const page = ref(1)
  const pageSize = ref(options.defaultPageSize ?? 10)
  const searchForm = reactive<TQuery>(options.createSearchForm())
  const selectedRows = ref<TRow[]>([])

  const selectedIds = computed(() => selectedRows.value.map((row) => row.id))

  async function fetchList(targetPage: number): Promise<void> {
    loading.value = true
    try {
      const result = await options.searchApi(targetPage, pageSize.value, { ...searchForm } as TQuery)
      list.value = result.data
      total.value = result.total
      page.value = targetPage
    } catch {
      // 拦截器已提示错误；兜底复位避免脏数据残留
      list.value = []
      total.value = 0
    } finally {
      loading.value = false
    }
  }

  /** 搜索：回第 1 页 */
  function handleSearch(): void {
    void fetchList(1)
  }

  /** 重置：重建搜索表单后查询 */
  function handleReset(): void {
    Object.assign(searchForm, options.createSearchForm())
    handleSearch()
  }

  function handleSizeChange(size: number): void {
    pageSize.value = size
    handleSearch()
  }

  function handleCurrentChange(current: number): void {
    void fetchList(current)
  }

  function handleSelectionChange(rows: TRow[]): void {
    selectedRows.value = rows
  }

  /** 刷新：保留当前页（删空当前页时由调用方决定回退，此处简单保留） */
  function refresh(): void {
    void fetchList(page.value)
  }

  /** 删除（单行传 [row.id]，批量传选中 ids）：确认 → 调接口 → 当前页保留刷新 */
  async function handleDelete(ids: number[]): Promise<void> {
    if (!options.destroyApi || ids.length === 0) {
      return
    }
    try {
      await ElMessageBox.confirm(`确定删除选中的 ${ids.length} 项吗？删除后不可恢复。`, '删除确认', {
        type: 'warning',
        confirmButtonText: '删除',
        cancelButtonText: '取消',
      })
    } catch {
      return // 用户取消
    }

    try {
      await options.destroyApi({ ids })
      ElMessage.success('删除成功')
      refresh()
    } catch {
      // 拦截器已提示错误；列表保持原状
    }
  }

  // 初始加载
  void fetchList(page.value)

  return {
    loading,
    list,
    total,
    page,
    pageSize,
    searchForm,
    selectedRows,
    selectedIds,
    fetchList,
    handleSearch,
    handleReset,
    handleSizeChange,
    handleCurrentChange,
    handleSelectionChange,
    handleDelete,
    refresh,
  }
}
