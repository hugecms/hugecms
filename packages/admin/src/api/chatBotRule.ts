import { post, get, put } from '@/utils/request'
import type { IChatBotRuleQueryRequest, IChatBotRuleQueryResponse, IChatBotRuleCreateRequest, IChatBotRuleResponse, IChatBotRuleUpdateRequest, IChatBotRuleDestroyRequest, IChatBotRuleDestroyResponse } from '@/types/chatBotRule'

/** [机器人自动回复规则模块] 查询机器人自动回复规则列表接口 */
export function chatBotRuleSearchApi(page: number, pageSize: number, data: IChatBotRuleQueryRequest): Promise<IChatBotRuleQueryResponse> {
  return post<IChatBotRuleQueryResponse>('admin/chatBotRule/search', data, { params: { page, pageSize } })
}

/** [机器人自动回复规则模块] 新增机器人自动回复规则接口 */
export function chatBotRuleStoreApi(data: IChatBotRuleCreateRequest): Promise<IChatBotRuleResponse> {
  return post<IChatBotRuleResponse>('admin/chatBotRule/store', data)
}

/** [机器人自动回复规则模块] 获取机器人自动回复规则详情接口 */
export function chatBotRuleShowApi(id: number): Promise<IChatBotRuleResponse> {
  return get<IChatBotRuleResponse>('admin/chatBotRule/show', { params: { id } })
}

/** [机器人自动回复规则模块] 更新机器人自动回复规则接口 */
export function chatBotRuleUpdateApi(id: number, data: IChatBotRuleUpdateRequest): Promise<IChatBotRuleResponse> {
  return put<IChatBotRuleResponse>('admin/chatBotRule/update', data, { params: { id } })
}

/** [机器人自动回复规则模块] 删除机器人自动回复规则接口 */
export function chatBotRuleDestroyApi(data: IChatBotRuleDestroyRequest): Promise<IChatBotRuleDestroyResponse> {
  return post<IChatBotRuleDestroyResponse>('admin/chatBotRule/destroy', data)
}
