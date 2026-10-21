import { post, get, put } from '@/utils/request'
import type { ISiteDailyStatisticsQueryRequest, ISiteDailyStatisticsQueryResponse, ISiteDailyStatisticsCreateRequest, ISiteDailyStatisticsResponse, ISiteDailyStatisticsUpdateRequest, ISiteDailyStatisticsDestroyRequest, ISiteDailyStatisticsDestroyResponse } from '@/types/siteDailyStatistics'

/** [每日统计汇总模块] 查询每日统计汇总列表接口 */
export function siteDailyStatisticsSearchApi(page: number, pageSize: number, data: ISiteDailyStatisticsQueryRequest): Promise<ISiteDailyStatisticsQueryResponse> {
  return post<ISiteDailyStatisticsQueryResponse>('admin/siteDailyStatistics/search', data, { params: { page, pageSize } })
}

/** [每日统计汇总模块] 新增每日统计汇总接口 */
export function siteDailyStatisticsStoreApi(data: ISiteDailyStatisticsCreateRequest): Promise<ISiteDailyStatisticsResponse> {
  return post<ISiteDailyStatisticsResponse>('admin/siteDailyStatistics/store', data)
}

/** [每日统计汇总模块] 获取每日统计汇总详情接口 */
export function siteDailyStatisticsShowApi(id: number): Promise<ISiteDailyStatisticsResponse> {
  return get<ISiteDailyStatisticsResponse>('admin/siteDailyStatistics/show', { params: { id } })
}

/** [每日统计汇总模块] 更新每日统计汇总接口 */
export function siteDailyStatisticsUpdateApi(id: number, data: ISiteDailyStatisticsUpdateRequest): Promise<ISiteDailyStatisticsResponse> {
  return put<ISiteDailyStatisticsResponse>('admin/siteDailyStatistics/update', data, { params: { id } })
}

/** [每日统计汇总模块] 删除每日统计汇总接口 */
export function siteDailyStatisticsDestroyApi(data: ISiteDailyStatisticsDestroyRequest): Promise<ISiteDailyStatisticsDestroyResponse> {
  return post<ISiteDailyStatisticsDestroyResponse>('admin/siteDailyStatistics/destroy', data)
}
