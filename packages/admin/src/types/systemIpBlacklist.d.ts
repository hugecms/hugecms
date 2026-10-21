export interface ISystemIpBlacklistCreateRequest {
  ip: string, // IP地址,支持IPv4/IPv6
  reason: string, // 拉黑原因
  expiredAt: string, // 过期时间,空=永久
  userType: string, // 操作账户类型:admin,system
  userId: number, // 操作人ID
}

export interface ISystemIpBlacklistDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemIpBlacklistQueryRequest {
  id?: number, // ID
  ip?: string, // IP地址,支持IPv4/IPv6
}

export interface ISystemIpBlacklistUpdateRequest {
  id: number, // ID
  ip: string, // IP地址,支持IPv4/IPv6
  reason: string, // 拉黑原因
  expiredAt: string, // 过期时间,空=永久
  userType: string, // 操作账户类型:admin,system
  userId: number, // 操作人ID
}

export interface ISystemIpBlacklistDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemIpBlacklistQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemIpBlacklistResponse[], // 数据列表
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

export interface ISystemIpBlacklistResponse {
  id: number, // ID
  ip: string, // IP地址,支持IPv4/IPv6
  reason: string, // 拉黑原因
  expiredAt: string, // 过期时间,空=永久
  userType: string, // 操作账户类型:admin,system
  userId: number, // 操作人ID
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

