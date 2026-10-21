import { post, get, put } from '@/utils/request'
import type { ISystemSettingQueryRequest, ISystemSettingQueryResponse, ISystemSettingCreateRequest, ISystemSettingResponse, ISystemSettingUpdateRequest, ISystemSettingDestroyRequest, ISystemSettingDestroyResponse } from '@/types/systemSetting'

/** [系统配置模块] 查询系统配置列表接口 */
export function systemSettingSearchApi(page: number, pageSize: number, data: ISystemSettingQueryRequest): Promise<ISystemSettingQueryResponse> {
  return post<ISystemSettingQueryResponse>('admin/systemSetting/search', data, { params: { page, pageSize } })
}

/** [系统配置模块] 新增系统配置接口 */
export function systemSettingStoreApi(data: ISystemSettingCreateRequest): Promise<ISystemSettingResponse> {
  return post<ISystemSettingResponse>('admin/systemSetting/store', data)
}

/** [系统配置模块] 获取系统配置详情接口 */
export function systemSettingShowApi(id: number): Promise<ISystemSettingResponse> {
  return get<ISystemSettingResponse>('admin/systemSetting/show', { params: { id } })
}

/** [系统配置模块] 更新系统配置接口 */
export function systemSettingUpdateApi(id: number, data: ISystemSettingUpdateRequest): Promise<ISystemSettingResponse> {
  return put<ISystemSettingResponse>('admin/systemSetting/update', data, { params: { id } })
}

/** [系统配置模块] 删除系统配置接口 */
export function systemSettingDestroyApi(data: ISystemSettingDestroyRequest): Promise<ISystemSettingDestroyResponse> {
  return post<ISystemSettingDestroyResponse>('admin/systemSetting/destroy', data)
}
