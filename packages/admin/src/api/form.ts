import { post, get, put } from '@/utils/request'
import type { IFormQueryRequest, IFormQueryResponse, IFormCreateRequest, IFormResponse, IFormUpdateRequest, IFormDestroyRequest, IFormDestroyResponse } from '@/types/form'

/** [自定义表单模块] 查询自定义表单列表接口 */
export function formSearchApi(page: number, pageSize: number, data: IFormQueryRequest): Promise<IFormQueryResponse> {
  return post<IFormQueryResponse>('admin/form/search', data, { params: { page, pageSize } })
}

/** [自定义表单模块] 新增自定义表单接口 */
export function formStoreApi(data: IFormCreateRequest): Promise<IFormResponse> {
  return post<IFormResponse>('admin/form/store', data)
}

/** [自定义表单模块] 获取自定义表单详情接口 */
export function formShowApi(id: number): Promise<IFormResponse> {
  return get<IFormResponse>('admin/form/show', { params: { id } })
}

/** [自定义表单模块] 更新自定义表单接口 */
export function formUpdateApi(id: number, data: IFormUpdateRequest): Promise<IFormResponse> {
  return put<IFormResponse>('admin/form/update', data, { params: { id } })
}

/** [自定义表单模块] 删除自定义表单接口 */
export function formDestroyApi(data: IFormDestroyRequest): Promise<IFormDestroyResponse> {
  return post<IFormDestroyResponse>('admin/form/destroy', data)
}
