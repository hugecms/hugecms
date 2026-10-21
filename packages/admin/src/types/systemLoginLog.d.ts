export interface ISystemLoginLogCreateRequest {
  userType: string, // 账户类型:admin=后台,user=前台
  userId: number, // 用户ID,账号不存在时为空
  username: string, // 登录账号(尝试值快照)
  status: number, // 结果:0=失败,1=成功
  ip: string, // 登录IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

export interface ISystemLoginLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemLoginLogQueryRequest {
  id?: number, // ID
  userType?: string, // 账户类型:admin=后台,user=前台
  userId?: number, // 用户ID,账号不存在时为空
  ip?: string, // 登录IP
  createTime?: string, // 创建时间
}

export interface ISystemLoginLogUpdateRequest {
  id: number, // ID
  userType: string, // 账户类型:admin=后台,user=前台
  userId: number, // 用户ID,账号不存在时为空
  username: string, // 登录账号(尝试值快照)
  status: number, // 结果:0=失败,1=成功
  ip: string, // 登录IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

export interface ISystemLoginLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemLoginLogQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemLoginLogResponse[], // 数据列表
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

export interface ISystemLoginLogResponse {
  id: number, // ID
  userType: string, // 账户类型:admin=后台,user=前台
  userId: number, // 用户ID,账号不存在时为空
  username: string, // 登录账号(尝试值快照)
  status: number, // 结果:0=失败,1=成功
  ip: string, // 登录IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

