import { post, get, put } from '@/utils/request'
import type { ISiteDailyStatisticQueryRequest, ISiteDailyStatisticQueryResponse, ISiteDailyStatisticCreateRequest, ISiteDailyStatisticResponse, ISiteDailyStatisticUpdateRequest, ISiteDailyStatisticDestroyRequest, ISiteDailyStatisticDestroyResponse } from '@/types/siteDailyStatistic'

/** [每日统计汇总模块] 查询每日统计汇总列表接口 */
export function siteDailyStatisticSearchApi(page: number, pageSize: number, data: ISiteDailyStatisticQueryRequest): Promise<ISiteDailyStatisticQueryResponse> {
  return post<ISiteDailyStatisticQueryResponse>('admin/siteDailyStatistic/search', data, { params: { page, pageSize } })
}

/** [每日统计汇总模块] 新增每日统计汇总接口 */
export function siteDailyStatisticStoreApi(data: ISiteDailyStatisticCreateRequest): Promise<ISiteDailyStatisticResponse> {
  return post<ISiteDailyStatisticResponse>('admin/siteDailyStatistic/store', data)
}

/** [每日统计汇总模块] 获取每日统计汇总详情接口 */
export function siteDailyStatisticShowApi(id: number): Promise<ISiteDailyStatisticResponse> {
  return get<ISiteDailyStatisticResponse>('admin/siteDailyStatistic/show', { params: { id } })
}

/** [每日统计汇总模块] 更新每日统计汇总接口 */
export function siteDailyStatisticUpdateApi(id: number, data: ISiteDailyStatisticUpdateRequest): Promise<ISiteDailyStatisticResponse> {
  return put<ISiteDailyStatisticResponse>('admin/siteDailyStatistic/update', data, { params: { id } })
}

/** [每日统计汇总模块] 删除每日统计汇总接口 */
export function siteDailyStatisticDestroyApi(data: ISiteDailyStatisticDestroyRequest): Promise<ISiteDailyStatisticDestroyResponse> {
  return post<ISiteDailyStatisticDestroyResponse>('admin/siteDailyStatistic/destroy', data)
}
