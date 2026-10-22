export interface IUserWithdrawOrderCreateRequest {
  withdrawNo: string, // 提现单号,应用层生成
  userId: number, // 用户ID
  amount: string, // 提现金额
  channel: string, // 打款渠道:manual=人工线下打款;微信商家转账后续扩展
  channelOrderNo: string, // 渠道打款单号,回填防重复打款
  accountType: string, // 收款方式:bank=银行卡,wechat=微信,alipay=支付宝
  accountName: string, // 收款人姓名
  accountNo: string, // 收款账号
  status: number, // 状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退
  userNote: string, // 用户备注
  adminNote: string, // 审核备注
  failReason: string, // 打款失败原因
  auditAt: string, // 审核时间
  paidAt: string, // 打款标记时间(成功或失败)
}

export interface IUserWithdrawOrderDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserWithdrawOrderQueryRequest {
  id?: number, // ID
  withdrawNo?: string, // 提现单号,应用层生成
  userId?: number, // 用户ID
  channel?: string, // 打款渠道:manual=人工线下打款;微信商家转账后续扩展
  channelOrderNo?: string, // 渠道打款单号,回填防重复打款
  status?: number, // 状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退
}

export interface IUserWithdrawOrderUpdateRequest {
  id: number, // ID
  withdrawNo: string, // 提现单号,应用层生成
  userId: number, // 用户ID
  amount: string, // 提现金额
  channel: string, // 打款渠道:manual=人工线下打款;微信商家转账后续扩展
  channelOrderNo: string, // 渠道打款单号,回填防重复打款
  accountType: string, // 收款方式:bank=银行卡,wechat=微信,alipay=支付宝
  accountName: string, // 收款人姓名
  accountNo: string, // 收款账号
  status: number, // 状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退
  userNote: string, // 用户备注
  adminNote: string, // 审核备注
  failReason: string, // 打款失败原因
  auditAt: string, // 审核时间
  paidAt: string, // 打款标记时间(成功或失败)
}

export interface IUserWithdrawOrderDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserWithdrawOrderQueryResponse {
  currentPage: number, // 当前页码
  data: IUserWithdrawOrderResponse[], // 数据列表
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

export interface IUserWithdrawOrderResponse {
  id: number, // ID
  withdrawNo: string, // 提现单号,应用层生成
  userId: number, // 用户ID
  amount: string, // 提现金额
  channel: string, // 打款渠道:manual=人工线下打款;微信商家转账后续扩展
  channelOrderNo: string, // 渠道打款单号,回填防重复打款
  accountType: string, // 收款方式:bank=银行卡,wechat=微信,alipay=支付宝
  accountName: string, // 收款人姓名
  accountNo: string, // 收款账号
  status: number, // 状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退
  userNote: string, // 用户备注
  adminNote: string, // 审核备注
  failReason: string, // 打款失败原因
  auditAt: string, // 审核时间
  paidAt: string, // 打款标记时间(成功或失败)
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

