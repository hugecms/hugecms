export interface IUserAccountLogCreateRequest {
  userId: number, // 用户ID
  bizType: number, // 业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整
  amount: string, // 变动金额,正数入负数出
  balanceAfter: string, // 变动后余额快照
  frozenAfter: string, // 变动后冻结快照
  bizNo: string, // 关联业务单号,如提现单号/佣金记录号/充值单号
  description: string, // 变动说明
  createTime: string, // 创建时间
}

export interface IUserAccountLogDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserAccountLogQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  bizNo?: string, // 关联业务单号,如提现单号/佣金记录号/充值单号
  createTime?: string, // 创建时间
}

export interface IUserAccountLogUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  bizType: number, // 业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整
  amount: string, // 变动金额,正数入负数出
  balanceAfter: string, // 变动后余额快照
  frozenAfter: string, // 变动后冻结快照
  bizNo: string, // 关联业务单号,如提现单号/佣金记录号/充值单号
  description: string, // 变动说明
  createTime: string, // 创建时间
}

export interface IUserAccountLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserAccountLogQueryResponse {
  currentPage: number, // 当前页码
  data: IUserAccountLogResponse[], // 数据列表
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

export interface IUserAccountLogResponse {
  id: number, // ID
  userId: number, // 用户ID
  bizType: number, // 业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整
  amount: string, // 变动金额,正数入负数出
  balanceAfter: string, // 变动后余额快照
  frozenAfter: string, // 变动后冻结快照
  bizNo: string, // 关联业务单号,如提现单号/佣金记录号/充值单号
  description: string, // 变动说明
  createTime: string, // 创建时间
}

