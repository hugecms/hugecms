export interface IUserAccountCreateRequest {
  userId: number, // 用户ID
  balance: string, // 账户余额,可为负=欠款,欠款不可用于消费/提现
  frozen: string, // 冻结金额,提现审核/打款中在途
}

export interface IUserAccountDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserAccountQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
}

export interface IUserAccountUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  balance: string, // 账户余额,可为负=欠款,欠款不可用于消费/提现
  frozen: string, // 冻结金额,提现审核/打款中在途
}

export interface IUserAccountDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserAccountQueryResponse {
  currentPage: number, // 当前页码
  data: IUserAccountResponse[], // 数据列表
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

export interface IUserAccountResponse {
  id: number, // ID
  userId: number, // 用户ID
  balance: string, // 账户余额,可为负=欠款,欠款不可用于消费/提现
  frozen: string, // 冻结金额,提现审核/打款中在途
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

