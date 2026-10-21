import { post, get, put } from '@/utils/request'
import type { ISystemAttachmentQueryRequest, ISystemAttachmentQueryResponse, ISystemAttachmentCreateRequest, ISystemAttachmentResponse, ISystemAttachmentUpdateRequest, ISystemAttachmentDestroyRequest, ISystemAttachmentDestroyResponse } from '@/types/systemAttachment'

/** [附件模块] 查询附件列表接口 */
export function systemAttachmentSearchApi(page: number, pageSize: number, data: ISystemAttachmentQueryRequest): Promise<ISystemAttachmentQueryResponse> {
  return post<ISystemAttachmentQueryResponse>('admin/systemAttachment/search', data, { params: { page, pageSize } })
}

/** [附件模块] 新增附件接口 */
export function systemAttachmentStoreApi(data: ISystemAttachmentCreateRequest): Promise<ISystemAttachmentResponse> {
  return post<ISystemAttachmentResponse>('admin/systemAttachment/store', data)
}

/** [附件模块] 获取附件详情接口 */
export function systemAttachmentShowApi(id: number): Promise<ISystemAttachmentResponse> {
  return get<ISystemAttachmentResponse>('admin/systemAttachment/show', { params: { id } })
}

/** [附件模块] 更新附件接口 */
export function systemAttachmentUpdateApi(id: number, data: ISystemAttachmentUpdateRequest): Promise<ISystemAttachmentResponse> {
  return put<ISystemAttachmentResponse>('admin/systemAttachment/update', data, { params: { id } })
}

/** [附件模块] 删除附件接口 */
export function systemAttachmentDestroyApi(data: ISystemAttachmentDestroyRequest): Promise<ISystemAttachmentDestroyResponse> {
  return post<ISystemAttachmentDestroyResponse>('admin/systemAttachment/destroy', data)
}
