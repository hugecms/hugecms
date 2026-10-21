import { post, get, put } from '@/utils/request'
import type { IMarketingDistributionCommissionRuleQueryRequest, IMarketingDistributionCommissionRuleQueryResponse, IMarketingDistributionCommissionRuleCreateRequest, IMarketingDistributionCommissionRuleResponse, IMarketingDistributionCommissionRuleUpdateRequest, IMarketingDistributionCommissionRuleDestroyRequest, IMarketingDistributionCommissionRuleDestroyResponse } from '@/types/marketingDistributionCommissionRule'

/** [佣金规则模块] 查询佣金规则列表接口 */
export function marketingDistributionCommissionRuleSearchApi(page: number, pageSize: number, data: IMarketingDistributionCommissionRuleQueryRequest): Promise<IMarketingDistributionCommissionRuleQueryResponse> {
  return post<IMarketingDistributionCommissionRuleQueryResponse>('admin/marketingDistributionCommissionRule/search', data, { params: { page, pageSize } })
}

/** [佣金规则模块] 新增佣金规则接口 */
export function marketingDistributionCommissionRuleStoreApi(data: IMarketingDistributionCommissionRuleCreateRequest): Promise<IMarketingDistributionCommissionRuleResponse> {
  return post<IMarketingDistributionCommissionRuleResponse>('admin/marketingDistributionCommissionRule/store', data)
}

/** [佣金规则模块] 获取佣金规则详情接口 */
export function marketingDistributionCommissionRuleShowApi(id: number): Promise<IMarketingDistributionCommissionRuleResponse> {
  return get<IMarketingDistributionCommissionRuleResponse>('admin/marketingDistributionCommissionRule/show', { params: { id } })
}

/** [佣金规则模块] 更新佣金规则接口 */
export function marketingDistributionCommissionRuleUpdateApi(id: number, data: IMarketingDistributionCommissionRuleUpdateRequest): Promise<IMarketingDistributionCommissionRuleResponse> {
  return put<IMarketingDistributionCommissionRuleResponse>('admin/marketingDistributionCommissionRule/update', data, { params: { id } })
}

/** [佣金规则模块] 删除佣金规则接口 */
export function marketingDistributionCommissionRuleDestroyApi(data: IMarketingDistributionCommissionRuleDestroyRequest): Promise<IMarketingDistributionCommissionRuleDestroyResponse> {
  return post<IMarketingDistributionCommissionRuleDestroyResponse>('admin/marketingDistributionCommissionRule/destroy', data)
}
