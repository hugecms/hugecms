import { post, get, put } from '@/utils/request'
import type { ISiteStatisticsDailyQueryRequest, ISiteStatisticsDailyQueryResponse, ISiteStatisticsDailyCreateRequest, ISiteStatisticsDailyResponse, ISiteStatisticsDailyUpdateRequest, ISiteStatisticsDailyDestroyRequest, ISiteStatisticsDailyDestroyResponse } from '@/types/siteStatisticsDaily'

/** [每日统计汇总模块] 查询每日统计汇总列表接口 */
export function siteStatisticsDailySearchApi(page: number, pageSize: number, data: ISiteStatisticsDailyQueryRequest): Promise<ISiteStatisticsDailyQueryResponse> {
  return post<ISiteStatisticsDailyQueryResponse>('admin/siteStatisticsDaily/search', data, { params: { page, pageSize } })
}

/** [每日统计汇总模块] 新增每日统计汇总接口 */
export function siteStatisticsDailyStoreApi(data: ISiteStatisticsDailyCreateRequest): Promise<ISiteStatisticsDailyResponse> {
  return post<ISiteStatisticsDailyResponse>('admin/siteStatisticsDaily/store', data)
}

/** [每日统计汇总模块] 获取每日统计汇总详情接口 */
export function siteStatisticsDailyShowApi(id: number): Promise<ISiteStatisticsDailyResponse> {
  return get<ISiteStatisticsDailyResponse>('admin/siteStatisticsDaily/show', { params: { id } })
}

/** [每日统计汇总模块] 更新每日统计汇总接口 */
export function siteStatisticsDailyUpdateApi(id: number, data: ISiteStatisticsDailyUpdateRequest): Promise<ISiteStatisticsDailyResponse> {
  return put<ISiteStatisticsDailyResponse>('admin/siteStatisticsDaily/update', data, { params: { id } })
}

/** [每日统计汇总模块] 删除每日统计汇总接口 */
export function siteStatisticsDailyDestroyApi(data: ISiteStatisticsDailyDestroyRequest): Promise<ISiteStatisticsDailyDestroyResponse> {
  return post<ISiteStatisticsDailyDestroyResponse>('admin/siteStatisticsDaily/destroy', data)
}
