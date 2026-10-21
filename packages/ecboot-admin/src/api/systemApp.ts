import { post, get, put } from '@/utils/request'
import type { ISystemAppQueryRequest, ISystemAppQueryResponse, ISystemAppCreateRequest, ISystemAppResponse, ISystemAppUpdateRequest, ISystemAppDestroyRequest, ISystemAppDestroyResponse } from '@/types/systemApp'

/** [应用注册模块] 查询应用注册列表接口 */
export function systemAppSearchApi(page: number, pageSize: number, data: ISystemAppQueryRequest): Promise<ISystemAppQueryResponse> {
  return post<ISystemAppQueryResponse>('admin/systemApp/search', data, { params: { page, pageSize } })
}

/** [应用注册模块] 新增应用注册接口 */
export function systemAppStoreApi(data: ISystemAppCreateRequest): Promise<ISystemAppResponse> {
  return post<ISystemAppResponse>('admin/systemApp/store', data)
}

/** [应用注册模块] 获取应用注册详情接口 */
export function systemAppShowApi(id: number): Promise<ISystemAppResponse> {
  return get<ISystemAppResponse>('admin/systemApp/show', { params: { id } })
}

/** [应用注册模块] 更新应用注册接口 */
export function systemAppUpdateApi(id: number, data: ISystemAppUpdateRequest): Promise<ISystemAppResponse> {
  return put<ISystemAppResponse>('admin/systemApp/update', data, { params: { id } })
}

/** [应用注册模块] 删除应用注册接口 */
export function systemAppDestroyApi(data: ISystemAppDestroyRequest): Promise<ISystemAppDestroyResponse> {
  return post<ISystemAppDestroyResponse>('admin/systemApp/destroy', data)
}
