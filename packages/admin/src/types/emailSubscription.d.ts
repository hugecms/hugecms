export interface IEmailSubscriptionCreateRequest {
  email: string, // 订阅邮箱
  status: number, // 状态:0=已退订,1=订阅中
  token: string, // 退订令牌
}

export interface IEmailSubscriptionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IEmailSubscriptionQueryRequest {
  id?: number, // ID
  email?: string, // 订阅邮箱
}

export interface IEmailSubscriptionUpdateRequest {
  id: number, // ID
  email: string, // 订阅邮箱
  status: number, // 状态:0=已退订,1=订阅中
  token: string, // 退订令牌
}

export interface IEmailSubscriptionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IEmailSubscriptionQueryResponse {
  currentPage: number, // 当前页码
  data: IEmailSubscriptionResponse[], // 数据列表
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

export interface IEmailSubscriptionResponse {
  id: number, // ID
  email: string, // 订阅邮箱
  status: number, // 状态:0=已退订,1=订阅中
  token: string, // 退订令牌
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

