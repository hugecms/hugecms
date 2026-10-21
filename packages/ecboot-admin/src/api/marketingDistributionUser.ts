import { post, get, put } from '@/utils/request'
import type { IMarketingDistributionUserQueryRequest, IMarketingDistributionUserQueryResponse, IMarketingDistributionUserCreateRequest, IMarketingDistributionUserResponse, IMarketingDistributionUserUpdateRequest, IMarketingDistributionUserDestroyRequest, IMarketingDistributionUserDestroyResponse } from '@/types/marketingDistributionUser'

/** [分销员模块] 查询分销员列表接口 */
export function marketingDistributionUserSearchApi(page: number, pageSize: number, data: IMarketingDistributionUserQueryRequest): Promise<IMarketingDistributionUserQueryResponse> {
  return post<IMarketingDistributionUserQueryResponse>('admin/marketingDistributionUser/search', data, { params: { page, pageSize } })
}

/** [分销员模块] 新增分销员接口 */
export function marketingDistributionUserStoreApi(data: IMarketingDistributionUserCreateRequest): Promise<IMarketingDistributionUserResponse> {
  return post<IMarketingDistributionUserResponse>('admin/marketingDistributionUser/store', data)
}

/** [分销员模块] 获取分销员详情接口 */
export function marketingDistributionUserShowApi(id: number): Promise<IMarketingDistributionUserResponse> {
  return get<IMarketingDistributionUserResponse>('admin/marketingDistributionUser/show', { params: { id } })
}

/** [分销员模块] 更新分销员接口 */
export function marketingDistributionUserUpdateApi(id: number, data: IMarketingDistributionUserUpdateRequest): Promise<IMarketingDistributionUserResponse> {
  return put<IMarketingDistributionUserResponse>('admin/marketingDistributionUser/update', data, { params: { id } })
}

/** [分销员模块] 删除分销员接口 */
export function marketingDistributionUserDestroyApi(data: IMarketingDistributionUserDestroyRequest): Promise<IMarketingDistributionUserDestroyResponse> {
  return post<IMarketingDistributionUserDestroyResponse>('admin/marketingDistributionUser/destroy', data)
}
