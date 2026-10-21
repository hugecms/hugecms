export interface IUserCreateRequest {
  username: string, // 用户名
  password: string, // 密码哈希
  email: string, // 邮箱
  phone: string, // 手机号
  status: number, // 状态:0=禁用,1=正常
  rankId: number, // 会员等级ID,0=默认等级
  balance: string, // 账户余额
  points: number, // 积分
  lastLoginTime: string, // 最后登录时间
  lastLoginIp: string, // 最后登录IP
}

export interface IUserDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserQueryRequest {
  id?: number, // ID
  username?: string, // 用户名
  email?: string, // 邮箱
  phone?: string, // 手机号
}

export interface IUserUpdateRequest {
  id: number, // ID
  username: string, // 用户名
  password: string, // 密码哈希
  email: string, // 邮箱
  phone: string, // 手机号
  status: number, // 状态:0=禁用,1=正常
  rankId: number, // 会员等级ID,0=默认等级
  balance: string, // 账户余额
  points: number, // 积分
  lastLoginTime: string, // 最后登录时间
  lastLoginIp: string, // 最后登录IP
}

export interface IUserDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserQueryResponse {
  currentPage: number, // 当前页码
  data: IUserResponse[], // 数据列表
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

export interface IUserResponse {
  id: number, // ID
  username: string, // 用户名
  email: string, // 邮箱
  phone: string, // 手机号
  status: number, // 状态:0=禁用,1=正常
  rankId: number, // 会员等级ID,0=默认等级
  balance: string, // 账户余额
  points: number, // 积分
  lastLoginTime: string, // 最后登录时间
  lastLoginIp: string, // 最后登录IP
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

