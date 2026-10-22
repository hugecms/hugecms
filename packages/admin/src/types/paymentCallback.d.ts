export interface IPaymentCallbackCreateRequest {
  paymentId: number, // 关联支付单ID,未匹配到支付单时为空
  channel: string, // 渠道:wechat=微信支付,alipay=支付宝
  eventType: string, // 事件类型:payment=支付通知,refund=退款通知
  notifyId: string, // 第三方通知唯一ID,幂等去重依据
  tradeNo: string, // 第三方交易号
  rawContent: string, // 原始报文(JSON)
  signVerified: number, // 验签结果:0=未通过,1=通过
  status: number, // 处理状态:0=未处理,1=已处理,2=异常忽略
  processedAt: string, // 处理时间
  createTime: string, // 创建时间
}

export interface IPaymentCallbackDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IPaymentCallbackQueryRequest {
  id?: number, // ID
  paymentId?: number, // 关联支付单ID,未匹配到支付单时为空
  channel?: string, // 渠道:wechat=微信支付,alipay=支付宝
  notifyId?: string, // 第三方通知唯一ID,幂等去重依据
  tradeNo?: string, // 第三方交易号
}

export interface IPaymentCallbackUpdateRequest {
  id: number, // ID
  paymentId: number, // 关联支付单ID,未匹配到支付单时为空
  channel: string, // 渠道:wechat=微信支付,alipay=支付宝
  eventType: string, // 事件类型:payment=支付通知,refund=退款通知
  notifyId: string, // 第三方通知唯一ID,幂等去重依据
  tradeNo: string, // 第三方交易号
  rawContent: string, // 原始报文(JSON)
  signVerified: number, // 验签结果:0=未通过,1=通过
  status: number, // 处理状态:0=未处理,1=已处理,2=异常忽略
  processedAt: string, // 处理时间
  createTime: string, // 创建时间
}

export interface IPaymentCallbackDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IPaymentCallbackQueryResponse {
  currentPage: number, // 当前页码
  data: IPaymentCallbackResponse[], // 数据列表
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

export interface IPaymentCallbackResponse {
  id: number, // ID
  paymentId: number, // 关联支付单ID,未匹配到支付单时为空
  channel: string, // 渠道:wechat=微信支付,alipay=支付宝
  eventType: string, // 事件类型:payment=支付通知,refund=退款通知
  notifyId: string, // 第三方通知唯一ID,幂等去重依据
  tradeNo: string, // 第三方交易号
  rawContent: string, // 原始报文(JSON)
  signVerified: number, // 验签结果:0=未通过,1=通过
  status: number, // 处理状态:0=未处理,1=已处理,2=异常忽略
  processedAt: string, // 处理时间
  createTime: string, // 创建时间
}

