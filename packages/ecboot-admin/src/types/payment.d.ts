export interface IPaymentCreateRequest {
  paymentNo: string, // 支付单号
  userId: number, // 付款用户ID
  bizType: string, // 业务类型:order=订单支付,recharge=余额充值
  bizId: number, // 业务单据ID,如:订单ID/充值申请ID
  payMethod: string, // 支付方式:wechat,alipay,balance
  appid: string, // 发起支付的应用AppID,关联system_apps,多端回调定位依据
  amount: string, // 支付金额
  status: number, // 状态:0=待支付,1=成功,2=失败
  tradeNo: string, // 第三方交易号
  paidAt: string, // 支付成功时间
}

export interface IPaymentDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IPaymentQueryRequest {
  id?: number, // ID
  paymentNo?: string, // 支付单号
  bizType?: string, // 业务类型:order=订单支付,recharge=余额充值
  bizId?: number, // 业务单据ID,如:订单ID/充值申请ID
  appid?: string, // 发起支付的应用AppID,关联system_apps,多端回调定位依据
  tradeNo?: string, // 第三方交易号
}

export interface IPaymentUpdateRequest {
  id: number, // ID
  paymentNo: string, // 支付单号
  userId: number, // 付款用户ID
  bizType: string, // 业务类型:order=订单支付,recharge=余额充值
  bizId: number, // 业务单据ID,如:订单ID/充值申请ID
  payMethod: string, // 支付方式:wechat,alipay,balance
  appid: string, // 发起支付的应用AppID,关联system_apps,多端回调定位依据
  amount: string, // 支付金额
  status: number, // 状态:0=待支付,1=成功,2=失败
  tradeNo: string, // 第三方交易号
  paidAt: string, // 支付成功时间
}

export interface IPaymentDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IPaymentQueryResponse {
  currentPage: number, // 当前页码
  data: IPaymentResponse[], // 数据列表
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

export interface IPaymentResponse {
  id: number, // ID
  paymentNo: string, // 支付单号
  userId: number, // 付款用户ID
  bizType: string, // 业务类型:order=订单支付,recharge=余额充值
  bizId: number, // 业务单据ID,如:订单ID/充值申请ID
  payMethod: string, // 支付方式:wechat,alipay,balance
  appid: string, // 发起支付的应用AppID,关联system_apps,多端回调定位依据
  amount: string, // 支付金额
  status: number, // 状态:0=待支付,1=成功,2=失败
  tradeNo: string, // 第三方交易号
  paidAt: string, // 支付成功时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

