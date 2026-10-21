export interface ISystemOperationLogCreateRequest {
  userType: string, // 账户类型:admin=后台,user=前台
  userId: number, // 用户ID
  username: string, // 操作人账号快照
  title: string, // 操作名称,如:删除用户
  controller: string, // 控制器
  action: string, // 方法
  method: string, // 请求方式
  url: string, // 请求地址
  params: string, // 请求参数(JSON)
  status: number, // 结果:0=失败,1=成功
  costTime: number, // 耗时(毫秒)
  ip: string, // 操作IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

export interface ISystemOperationLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISystemOperationLogQueryRequest {
  id?: number, // ID
  userType?: string, // 账户类型:admin=后台,user=前台
  userId?: number, // 用户ID
  createTime?: string, // 创建时间
}

export interface ISystemOperationLogUpdateRequest {
  id: number, // ID
  userType: string, // 账户类型:admin=后台,user=前台
  userId: number, // 用户ID
  username: string, // 操作人账号快照
  title: string, // 操作名称,如:删除用户
  controller: string, // 控制器
  action: string, // 方法
  method: string, // 请求方式
  url: string, // 请求地址
  params: string, // 请求参数(JSON)
  status: number, // 结果:0=失败,1=成功
  costTime: number, // 耗时(毫秒)
  ip: string, // 操作IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

export interface ISystemOperationLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISystemOperationLogQueryResponse {
  currentPage: number, // 当前页码
  data: ISystemOperationLogResponse[], // 数据列表
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

export interface ISystemOperationLogResponse {
  id: number, // ID
  userType: string, // 账户类型:admin=后台,user=前台
  userId: number, // 用户ID
  username: string, // 操作人账号快照
  title: string, // 操作名称,如:删除用户
  controller: string, // 控制器
  action: string, // 方法
  method: string, // 请求方式
  url: string, // 请求地址
  params: string, // 请求参数(JSON)
  status: number, // 结果:0=失败,1=成功
  costTime: number, // 耗时(毫秒)
  ip: string, // 操作IP
  userAgent: string, // User-Agent
  createTime: string, // 创建时间
}

