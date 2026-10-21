export interface IUserProfileCreateRequest {
  userId: number, // 用户ID
  nickname: string, // 昵称
  avatar: string, // 头像
  gender: number, // 性别:0=未知,1=男,2=女
  birthday: string, // 生日
  bio: string, // 个人简介
  location: string, // 所在地
}

export interface IUserProfileDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserProfileQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
}

export interface IUserProfileUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  nickname: string, // 昵称
  avatar: string, // 头像
  gender: number, // 性别:0=未知,1=男,2=女
  birthday: string, // 生日
  bio: string, // 个人简介
  location: string, // 所在地
}

export interface IUserProfileDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserProfileQueryResponse {
  currentPage: number, // 当前页码
  data: IUserProfileResponse[], // 数据列表
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

export interface IUserProfileResponse {
  id: number, // ID
  userId: number, // 用户ID
  nickname: string, // 昵称
  avatar: string, // 头像
  gender: number, // 性别:0=未知,1=男,2=女
  birthday: string, // 生日
  bio: string, // 个人简介
  location: string, // 所在地
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

