export interface IUserFundApplicationCreateRequest {
  userId: number, // 用户ID
  type: string, // 类型:recharge=充值,withdraw=提现
  amount: string, // 申请金额
  status: number, // 状态:0=待审核,1=已到账,2=已拒绝
  userNote: string, // 用户备注
  adminNote: string, // 管理员备注
  paidAt: string, // 到账时间
}

export interface IUserFundApplicationDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserFundApplicationQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  status?: number, // 状态:0=待审核,1=已到账,2=已拒绝
}

export interface IUserFundApplicationUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  type: string, // 类型:recharge=充值,withdraw=提现
  amount: string, // 申请金额
  status: number, // 状态:0=待审核,1=已到账,2=已拒绝
  userNote: string, // 用户备注
  adminNote: string, // 管理员备注
  paidAt: string, // 到账时间
}

export interface IUserFundApplicationDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserFundApplicationQueryResponse {
  currentPage: number, // 当前页码
  data: IUserFundApplicationResponse[], // 数据列表
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

export interface IUserFundApplicationResponse {
  id: number, // ID
  userId: number, // 用户ID
  type: string, // 类型:recharge=充值,withdraw=提现
  amount: string, // 申请金额
  status: number, // 状态:0=待审核,1=已到账,2=已拒绝
  userNote: string, // 用户备注
  adminNote: string, // 管理员备注
  paidAt: string, // 到账时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

