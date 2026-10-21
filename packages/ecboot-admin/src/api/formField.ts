import { post, get, put } from '@/utils/request'
import type { IFormFieldQueryRequest, IFormFieldQueryResponse, IFormFieldCreateRequest, IFormFieldResponse, IFormFieldUpdateRequest, IFormFieldDestroyRequest, IFormFieldDestroyResponse } from '@/types/formField'

/** [表单字段模块] 查询表单字段列表接口 */
export function formFieldSearchApi(page: number, pageSize: number, data: IFormFieldQueryRequest): Promise<IFormFieldQueryResponse> {
  return post<IFormFieldQueryResponse>('admin/formField/search', data, { params: { page, pageSize } })
}

/** [表单字段模块] 新增表单字段接口 */
export function formFieldStoreApi(data: IFormFieldCreateRequest): Promise<IFormFieldResponse> {
  return post<IFormFieldResponse>('admin/formField/store', data)
}

/** [表单字段模块] 获取表单字段详情接口 */
export function formFieldShowApi(id: number): Promise<IFormFieldResponse> {
  return get<IFormFieldResponse>('admin/formField/show', { params: { id } })
}

/** [表单字段模块] 更新表单字段接口 */
export function formFieldUpdateApi(id: number, data: IFormFieldUpdateRequest): Promise<IFormFieldResponse> {
  return put<IFormFieldResponse>('admin/formField/update', data, { params: { id } })
}

/** [表单字段模块] 删除表单字段接口 */
export function formFieldDestroyApi(data: IFormFieldDestroyRequest): Promise<IFormFieldDestroyResponse> {
  return post<IFormFieldDestroyResponse>('admin/formField/destroy', data)
}
