export interface IChatServiceCreateRequest {
  adminUserId: number, // 客服管理员ID,关联admin_users
  onlineStatus: number, // 在线状态:0=离线,1=在线,2=忙碌
  maxSessions: number, // 最大接待会话数
  currentSessions: number, // 当前接待数,分配依据,冗余计数
}

export interface IChatServiceDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IChatServiceQueryRequest {
  id?: number, // ID
  adminUserId?: number, // 客服管理员ID,关联admin_users
}

export interface IChatServiceUpdateRequest {
  id: number, // ID
  adminUserId: number, // 客服管理员ID,关联admin_users
  onlineStatus: number, // 在线状态:0=离线,1=在线,2=忙碌
  maxSessions: number, // 最大接待会话数
  currentSessions: number, // 当前接待数,分配依据,冗余计数
}

export interface IChatServiceDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IChatServiceQueryResponse {
  currentPage: number, // 当前页码
  data: IChatServiceResponse[], // 数据列表
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

export interface IChatServiceResponse {
  id: number, // ID
  adminUserId: number, // 客服管理员ID,关联admin_users
  onlineStatus: number, // 在线状态:0=离线,1=在线,2=忙碌
  maxSessions: number, // 最大接待会话数
  currentSessions: number, // 当前接待数,分配依据,冗余计数
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

