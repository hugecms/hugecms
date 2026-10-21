export interface IChatQuickReplyCreateRequest {
  group: string, // 分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流
  title: string, // 话术标题
  content: string, // 话术内容
  useCount: number, // 使用次数
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用
}

export interface IChatQuickReplyDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IChatQuickReplyQueryRequest {
  id?: number, // ID
  group?: string, // 分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流
}

export interface IChatQuickReplyUpdateRequest {
  id: number, // ID
  group: string, // 分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流
  title: string, // 话术标题
  content: string, // 话术内容
  useCount: number, // 使用次数
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用
}

export interface IChatQuickReplyDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IChatQuickReplyQueryResponse {
  currentPage: number, // 当前页码
  data: IChatQuickReplyResponse[], // 数据列表
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

export interface IChatQuickReplyResponse {
  id: number, // ID
  group: string, // 分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流
  title: string, // 话术标题
  content: string, // 话术内容
  useCount: number, // 使用次数
  sort: number, // 排序
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

