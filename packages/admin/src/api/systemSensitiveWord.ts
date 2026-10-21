import { post, get, put } from '@/utils/request'
import type { ISystemSensitiveWordQueryRequest, ISystemSensitiveWordQueryResponse, ISystemSensitiveWordCreateRequest, ISystemSensitiveWordResponse, ISystemSensitiveWordUpdateRequest, ISystemSensitiveWordDestroyRequest, ISystemSensitiveWordDestroyResponse } from '@/types/systemSensitiveWord'

/** [敏感词模块] 查询敏感词列表接口 */
export function systemSensitiveWordSearchApi(page: number, pageSize: number, data: ISystemSensitiveWordQueryRequest): Promise<ISystemSensitiveWordQueryResponse> {
  return post<ISystemSensitiveWordQueryResponse>('admin/systemSensitiveWord/search', data, { params: { page, pageSize } })
}

/** [敏感词模块] 新增敏感词接口 */
export function systemSensitiveWordStoreApi(data: ISystemSensitiveWordCreateRequest): Promise<ISystemSensitiveWordResponse> {
  return post<ISystemSensitiveWordResponse>('admin/systemSensitiveWord/store', data)
}

/** [敏感词模块] 获取敏感词详情接口 */
export function systemSensitiveWordShowApi(id: number): Promise<ISystemSensitiveWordResponse> {
  return get<ISystemSensitiveWordResponse>('admin/systemSensitiveWord/show', { params: { id } })
}

/** [敏感词模块] 更新敏感词接口 */
export function systemSensitiveWordUpdateApi(id: number, data: ISystemSensitiveWordUpdateRequest): Promise<ISystemSensitiveWordResponse> {
  return put<ISystemSensitiveWordResponse>('admin/systemSensitiveWord/update', data, { params: { id } })
}

/** [敏感词模块] 删除敏感词接口 */
export function systemSensitiveWordDestroyApi(data: ISystemSensitiveWordDestroyRequest): Promise<ISystemSensitiveWordDestroyResponse> {
  return post<ISystemSensitiveWordDestroyResponse>('admin/systemSensitiveWord/destroy', data)
}
