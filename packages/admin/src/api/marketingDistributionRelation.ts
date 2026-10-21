import { post, get, put } from '@/utils/request'
import type { IMarketingDistributionRelationQueryRequest, IMarketingDistributionRelationQueryResponse, IMarketingDistributionRelationCreateRequest, IMarketingDistributionRelationResponse, IMarketingDistributionRelationUpdateRequest, IMarketingDistributionRelationDestroyRequest, IMarketingDistributionRelationDestroyResponse } from '@/types/marketingDistributionRelation'

/** [分销绑定关系模块] 查询分销绑定关系列表接口 */
export function marketingDistributionRelationSearchApi(page: number, pageSize: number, data: IMarketingDistributionRelationQueryRequest): Promise<IMarketingDistributionRelationQueryResponse> {
  return post<IMarketingDistributionRelationQueryResponse>('admin/marketingDistributionRelation/search', data, { params: { page, pageSize } })
}

/** [分销绑定关系模块] 新增分销绑定关系接口 */
export function marketingDistributionRelationStoreApi(data: IMarketingDistributionRelationCreateRequest): Promise<IMarketingDistributionRelationResponse> {
  return post<IMarketingDistributionRelationResponse>('admin/marketingDistributionRelation/store', data)
}

/** [分销绑定关系模块] 获取分销绑定关系详情接口 */
export function marketingDistributionRelationShowApi(id: number): Promise<IMarketingDistributionRelationResponse> {
  return get<IMarketingDistributionRelationResponse>('admin/marketingDistributionRelation/show', { params: { id } })
}

/** [分销绑定关系模块] 更新分销绑定关系接口 */
export function marketingDistributionRelationUpdateApi(id: number, data: IMarketingDistributionRelationUpdateRequest): Promise<IMarketingDistributionRelationResponse> {
  return put<IMarketingDistributionRelationResponse>('admin/marketingDistributionRelation/update', data, { params: { id } })
}

/** [分销绑定关系模块] 删除分销绑定关系接口 */
export function marketingDistributionRelationDestroyApi(data: IMarketingDistributionRelationDestroyRequest): Promise<IMarketingDistributionRelationDestroyResponse> {
  return post<IMarketingDistributionRelationDestroyResponse>('admin/marketingDistributionRelation/destroy', data)
}
