export interface ISystemAttachmentCreateRequest {
  storage: string, // 存储驱动:local,oss,qiniu
  filePath: string, // 文件路径
  fileName: string, // 原始文件名
  mimeType: string, // MIME类型
  fileSize: number, // 文件大小(字节)
  module: string, // 所属模块:article,product
  userType: string, // 上传账户类型:admin,user
  userId: number, // 上传人ID
  createTime: string, // 创建时间
}

export interface ISystemAttachmentDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemAttachmentQueryRequest {
  id?: number, // ID
  module?: string, // 所属模块:article,product
}

export interface ISystemAttachmentUpdateRequest {
  id: number, // ID
  storage: string, // 存储驱动:local,oss,qiniu
  filePath: string, // 文件路径
  fileName: string, // 原始文件名
  mimeType: string, // MIME类型
  fileSize: number, // 文件大小(字节)
  module: string, // 所属模块:article,product
  userType: string, // 上传账户类型:admin,user
  userId: number, // 上传人ID
  createTime: string, // 创建时间
}

export interface ISystemAttachmentDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemAttachmentQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemAttachmentResponse[], // 数据列表
  firstPageUrl: string, // 首页URL
  from: number, // 当前页面上的开始位置
  lastPage: number, // 最后页码
  lastPageUrl: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  nextPageUrl: string, // 下一页URL
  path: string, // 分页URL
  perPage: number, // 每页显示的记录数量
  prevPageUrl: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface ISystemAttachmentResponse {
  id: number, // ID
  storage: string, // 存储驱动:local,oss,qiniu
  filePath: string, // 文件路径
  fileName: string, // 原始文件名
  mimeType: string, // MIME类型
  fileSize: number, // 文件大小(字节)
  module: string, // 所属模块:article,product
  userType: string, // 上传账户类型:admin,user
  userId: number, // 上传人ID
  createTime: string, // 创建时间
}

