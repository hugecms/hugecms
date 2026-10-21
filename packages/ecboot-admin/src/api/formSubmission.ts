import { post, get, put } from '@/utils/request'
import type { IFormSubmissionQueryRequest, IFormSubmissionQueryResponse, IFormSubmissionCreateRequest, IFormSubmissionResponse, IFormSubmissionUpdateRequest, IFormSubmissionDestroyRequest, IFormSubmissionDestroyResponse } from '@/types/formSubmission'

/** [表单提交数据模块] 查询表单提交数据列表接口 */
export function formSubmissionSearchApi(page: number, pageSize: number, data: IFormSubmissionQueryRequest): Promise<IFormSubmissionQueryResponse> {
  return post<IFormSubmissionQueryResponse>('admin/formSubmission/search', data, { params: { page, pageSize } })
}

/** [表单提交数据模块] 新增表单提交数据接口 */
export function formSubmissionStoreApi(data: IFormSubmissionCreateRequest): Promise<IFormSubmissionResponse> {
  return post<IFormSubmissionResponse>('admin/formSubmission/store', data)
}

/** [表单提交数据模块] 获取表单提交数据详情接口 */
export function formSubmissionShowApi(id: number): Promise<IFormSubmissionResponse> {
  return get<IFormSubmissionResponse>('admin/formSubmission/show', { params: { id } })
}

/** [表单提交数据模块] 更新表单提交数据接口 */
export function formSubmissionUpdateApi(id: number, data: IFormSubmissionUpdateRequest): Promise<IFormSubmissionResponse> {
  return put<IFormSubmissionResponse>('admin/formSubmission/update', data, { params: { id } })
}

/** [表单提交数据模块] 删除表单提交数据接口 */
export function formSubmissionDestroyApi(data: IFormSubmissionDestroyRequest): Promise<IFormSubmissionDestroyResponse> {
  return post<IFormSubmissionDestroyResponse>('admin/formSubmission/destroy', data)
}
