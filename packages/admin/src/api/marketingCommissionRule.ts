import { post, get, put } from '@/utils/request'
import type { IMarketingCommissionRuleQueryRequest, IMarketingCommissionRuleQueryResponse, IMarketingCommissionRuleCreateRequest, IMarketingCommissionRuleResponse, IMarketingCommissionRuleUpdateRequest, IMarketingCommissionRuleDestroyRequest, IMarketingCommissionRuleDestroyResponse } from '@/types/marketingCommissionRule'

/** [佣金规则模块] 查询佣金规则列表接口 */
export function marketingCommissionRuleSearchApi(page: number, pageSize: number, data: IMarketingCommissionRuleQueryRequest): Promise<IMarketingCommissionRuleQueryResponse> {
  return post<IMarketingCommissionRuleQueryResponse>('admin/marketingCommissionRule/search', data, { params: { page, pageSize } })
}

/** [佣金规则模块] 新增佣金规则接口 */
export function marketingCommissionRuleStoreApi(data: IMarketingCommissionRuleCreateRequest): Promise<IMarketingCommissionRuleResponse> {
  return post<IMarketingCommissionRuleResponse>('admin/marketingCommissionRule/store', data)
}

/** [佣金规则模块] 获取佣金规则详情接口 */
export function marketingCommissionRuleShowApi(id: number): Promise<IMarketingCommissionRuleResponse> {
  return get<IMarketingCommissionRuleResponse>('admin/marketingCommissionRule/show', { params: { id } })
}

/** [佣金规则模块] 更新佣金规则接口 */
export function marketingCommissionRuleUpdateApi(id: number, data: IMarketingCommissionRuleUpdateRequest): Promise<IMarketingCommissionRuleResponse> {
  return put<IMarketingCommissionRuleResponse>('admin/marketingCommissionRule/update', data, { params: { id } })
}

/** [佣金规则模块] 删除佣金规则接口 */
export function marketingCommissionRuleDestroyApi(data: IMarketingCommissionRuleDestroyRequest): Promise<IMarketingCommissionRuleDestroyResponse> {
  return post<IMarketingCommissionRuleDestroyResponse>('admin/marketingCommissionRule/destroy', data)
}
