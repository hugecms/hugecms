export interface IUserSmsCodeCreateRequest {
  phone: string, // 手机号
  code: string, // 验证码
  scene: string, // 场景:register=注册,login=登录,reset_password=重置密码
  expireAt: string, // 过期时间
  usedAt: string, // 使用时间
  ip: string, // 发送IP,用于防刷
  createTime: string, // 创建时间
}

export interface IUserSmsCodeDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserSmsCodeQueryRequest {
  id?: number, // ID
  phone?: string, // 手机号
  scene?: string, // 场景:register=注册,login=登录,reset_password=重置密码
  ip?: string, // 发送IP,用于防刷
  createTime?: string, // 创建时间
}

export interface IUserSmsCodeUpdateRequest {
  id: number, // ID
  phone: string, // 手机号
  code: string, // 验证码
  scene: string, // 场景:register=注册,login=登录,reset_password=重置密码
  expireAt: string, // 过期时间
  usedAt: string, // 使用时间
  ip: string, // 发送IP,用于防刷
  createTime: string, // 创建时间
}

export interface IUserSmsCodeDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserSmsCodeQueryResponse {
  currentPage: number, // 当前页码
  data: IUserSmsCodeResponse[], // 数据列表
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

export interface IUserSmsCodeResponse {
  id: number, // ID
  phone: string, // 手机号
  code: string, // 验证码
  scene: string, // 场景:register=注册,login=登录,reset_password=重置密码
  expireAt: string, // 过期时间
  usedAt: string, // 使用时间
  ip: string, // 发送IP,用于防刷
  createTime: string, // 创建时间
}

