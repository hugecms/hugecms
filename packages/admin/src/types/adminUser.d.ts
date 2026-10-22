export interface IAdminUserCreateRequest {
  username: string, // 用户名
  email: string, // 邮箱
  phone: string, // 手机号
  password: string, // 密码哈希
  status: number, // 状态:0=禁用,1=正常
  isSuper: number, // 超级管理员:0=否,1=是;超管不受角色权限限制
  lastLoginTime: string, // 最后登录时间
  lastLoginIp: string, // 最后登录IP
}

export interface IAdminUserDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IAdminUserQueryRequest {
  id?: number, // ID
  username?: string, // 用户名
  email?: string, // 邮箱
  phone?: string, // 手机号
}

export interface IAdminUserUpdateRequest {
  id: number, // ID
  username: string, // 用户名
  email: string, // 邮箱
  phone: string, // 手机号
  password: string, // 密码哈希
  status: number, // 状态:0=禁用,1=正常
  isSuper: number, // 超级管理员:0=否,1=是;超管不受角色权限限制
  lastLoginTime: string, // 最后登录时间
  lastLoginIp: string, // 最后登录IP
}

export interface IAdminUserDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IAdminUserQueryResponse {
  currentPage: number, // 当前页码
  data: IAdminUserResponse[], // 数据列表
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

export interface IAdminUserResponse {
  id: number, // ID
  username: string, // 用户名
  email: string, // 邮箱
  phone: string, // 手机号
  status: number, // 状态:0=禁用,1=正常
  isSuper: number, // 超级管理员:0=否,1=是;超管不受角色权限限制
  lastLoginTime: string, // 最后登录时间
  lastLoginIp: string, // 最后登录IP
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

