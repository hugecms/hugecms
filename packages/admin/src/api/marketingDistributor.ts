import { post, get, put } from '@/utils/request'
import type { IMarketingDistributorQueryRequest, IMarketingDistributorQueryResponse, IMarketingDistributorCreateRequest, IMarketingDistributorResponse, IMarketingDistributorUpdateRequest, IMarketingDistributorDestroyRequest, IMarketingDistributorDestroyResponse } from '@/types/marketingDistributor'

/** [分销员模块] 查询分销员列表接口 */
export function marketingDistributorSearchApi(page: number, pageSize: number, data: IMarketingDistributorQueryRequest): Promise<IMarketingDistributorQueryResponse> {
  return post<IMarketingDistributorQueryResponse>('admin/marketingDistributor/search', data, { params: { page, pageSize } })
}

/** [分销员模块] 新增分销员接口 */
export function marketingDistributorStoreApi(data: IMarketingDistributorCreateRequest): Promise<IMarketingDistributorResponse> {
  return post<IMarketingDistributorResponse>('admin/marketingDistributor/store', data)
}

/** [分销员模块] 获取分销员详情接口 */
export function marketingDistributorShowApi(id: number): Promise<IMarketingDistributorResponse> {
  return get<IMarketingDistributorResponse>('admin/marketingDistributor/show', { params: { id } })
}

/** [分销员模块] 更新分销员接口 */
export function marketingDistributorUpdateApi(id: number, data: IMarketingDistributorUpdateRequest): Promise<IMarketingDistributorResponse> {
  return put<IMarketingDistributorResponse>('admin/marketingDistributor/update', data, { params: { id } })
}

/** [分销员模块] 删除分销员接口 */
export function marketingDistributorDestroyApi(data: IMarketingDistributorDestroyRequest): Promise<IMarketingDistributorDestroyResponse> {
  return post<IMarketingDistributorDestroyResponse>('admin/marketingDistributor/destroy', data)
}
