import { get } from '@/utils/request'
import type { IDashboardTodoResponse } from '@/types/dashboard'

/** [仪表盘模块] 获取待办计数 */
export function dashboardTodoApi(): Promise<IDashboardTodoResponse> {
  return get<IDashboardTodoResponse>('admin/dashboard/todo')
}
