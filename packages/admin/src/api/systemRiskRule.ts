import { post, get, put } from '@/utils/request'
import type { ISystemRiskRuleQueryRequest, ISystemRiskRuleQueryResponse, ISystemRiskRuleCreateRequest, ISystemRiskRuleResponse, ISystemRiskRuleUpdateRequest, ISystemRiskRuleDestroyRequest, ISystemRiskRuleDestroyResponse } from '@/types/systemRiskRule'

/** [风控规则模块] 查询风控规则列表接口 */
export function systemRiskRuleSearchApi(page: number, pageSize: number, data: ISystemRiskRuleQueryRequest): Promise<ISystemRiskRuleQueryResponse> {
  return post<ISystemRiskRuleQueryResponse>('admin/systemRiskRule/search', data, { params: { page, pageSize } })
}

/** [风控规则模块] 新增风控规则接口 */
export function systemRiskRuleStoreApi(data: ISystemRiskRuleCreateRequest): Promise<ISystemRiskRuleResponse> {
  return post<ISystemRiskRuleResponse>('admin/systemRiskRule/store', data)
}

/** [风控规则模块] 获取风控规则详情接口 */
export function systemRiskRuleShowApi(id: number): Promise<ISystemRiskRuleResponse> {
  return get<ISystemRiskRuleResponse>('admin/systemRiskRule/show', { params: { id } })
}

/** [风控规则模块] 更新风控规则接口 */
export function systemRiskRuleUpdateApi(id: number, data: ISystemRiskRuleUpdateRequest): Promise<ISystemRiskRuleResponse> {
  return put<ISystemRiskRuleResponse>('admin/systemRiskRule/update', data, { params: { id } })
}

/** [风控规则模块] 删除风控规则接口 */
export function systemRiskRuleDestroyApi(data: ISystemRiskRuleDestroyRequest): Promise<ISystemRiskRuleDestroyResponse> {
  return post<ISystemRiskRuleDestroyResponse>('admin/systemRiskRule/destroy', data)
}
