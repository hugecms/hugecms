export interface IFormSubmissionCreateRequest {
  formId: number, // 表单ID
  userId: number, // 提交用户ID,游客为空
  data: string, // 提交数据(JSON,{字段标识:值})
  status: number, // 处理状态:0=未处理,1=已处理
  ip: string, // 提交IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

export interface IFormSubmissionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IFormSubmissionQueryRequest {
  id?: number, // ID
  formId?: number, // 表单ID
  userId?: number, // 提交用户ID,游客为空
  createTime?: string, // 创建时间
}

export interface IFormSubmissionUpdateRequest {
  id: number, // ID
  formId: number, // 表单ID
  userId: number, // 提交用户ID,游客为空
  data: string, // 提交数据(JSON,{字段标识:值})
  status: number, // 处理状态:0=未处理,1=已处理
  ip: string, // 提交IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

export interface IFormSubmissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IFormSubmissionQueryResponse {
  currentPage: number, // 当前页码
  data: IFormSubmissionResponse[], // 数据列表
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

export interface IFormSubmissionResponse {
  id: number, // ID
  formId: number, // 表单ID
  userId: number, // 提交用户ID,游客为空
  data: string, // 提交数据(JSON,{字段标识:值})
  status: number, // 处理状态:0=未处理,1=已处理
  ip: string, // 提交IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

