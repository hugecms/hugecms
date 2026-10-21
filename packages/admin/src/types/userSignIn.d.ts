export interface IUserSignInCreateRequest {
  userId: number, // 用户ID
  signDate: string, // 签到日期
  continuousDays: number, // 连续签到天数
  points: number, // 本次获得积分
  createTime: string, // 创建时间
}

export interface IUserSignInDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserSignInQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  signDate?: string, // 签到日期
}

export interface IUserSignInUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  signDate: string, // 签到日期
  continuousDays: number, // 连续签到天数
  points: number, // 本次获得积分
  createTime: string, // 创建时间
}

export interface IUserSignInDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserSignInQueryResponse {
  currentPage: number, // 当前页码
  data: IUserSignInResponse[], // 数据列表
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

export interface IUserSignInResponse {
  id: number, // ID
  userId: number, // 用户ID
  signDate: string, // 签到日期
  continuousDays: number, // 连续签到天数
  points: number, // 本次获得积分
  createTime: string, // 创建时间
}

