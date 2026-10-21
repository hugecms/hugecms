import { post, get, put } from '@/utils/request'
import type { IMarketingDistributionCommissionQueryRequest, IMarketingDistributionCommissionQueryResponse, IMarketingDistributionCommissionCreateRequest, IMarketingDistributionCommissionResponse, IMarketingDistributionCommissionUpdateRequest, IMarketingDistributionCommissionDestroyRequest, IMarketingDistributionCommissionDestroyResponse } from '@/types/marketingDistributionCommission'

/** [分销佣金流水模块] 查询分销佣金流水列表接口 */
export function marketingDistributionCommissionSearchApi(page: number, pageSize: number, data: IMarketingDistributionCommissionQueryRequest): Promise<IMarketingDistributionCommissionQueryResponse> {
  return post<IMarketingDistributionCommissionQueryResponse>('admin/marketingDistributionCommission/search', data, { params: { page, pageSize } })
}

/** [分销佣金流水模块] 新增分销佣金流水接口 */
export function marketingDistributionCommissionStoreApi(data: IMarketingDistributionCommissionCreateRequest): Promise<IMarketingDistributionCommissionResponse> {
  return post<IMarketingDistributionCommissionResponse>('admin/marketingDistributionCommission/store', data)
}

/** [分销佣金流水模块] 获取分销佣金流水详情接口 */
export function marketingDistributionCommissionShowApi(id: number): Promise<IMarketingDistributionCommissionResponse> {
  return get<IMarketingDistributionCommissionResponse>('admin/marketingDistributionCommission/show', { params: { id } })
}

/** [分销佣金流水模块] 更新分销佣金流水接口 */
export function marketingDistributionCommissionUpdateApi(id: number, data: IMarketingDistributionCommissionUpdateRequest): Promise<IMarketingDistributionCommissionResponse> {
  return put<IMarketingDistributionCommissionResponse>('admin/marketingDistributionCommission/update', data, { params: { id } })
}

/** [分销佣金流水模块] 删除分销佣金流水接口 */
export function marketingDistributionCommissionDestroyApi(data: IMarketingDistributionCommissionDestroyRequest): Promise<IMarketingDistributionCommissionDestroyResponse> {
  return post<IMarketingDistributionCommissionDestroyResponse>('admin/marketingDistributionCommission/destroy', data)
}
