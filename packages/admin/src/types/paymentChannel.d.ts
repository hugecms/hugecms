export interface IPaymentChannelCreateRequest {
  code: string, // 渠道标识:wechat=微信支付,alipay=支付宝,balance=余额
  appid: string, // 应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号
  name: string, // 渠道名称
  config: string, // 渠道配置(JSON):商户号/密钥等
  status: number, // 状态:0=停用,1=启用
  sort: number, // 排序
}

export interface IPaymentChannelDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IPaymentChannelQueryRequest {
  id?: number, // ID
  code?: string, // 渠道标识:wechat=微信支付,alipay=支付宝,balance=余额
  appid?: string, // 应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号
}

export interface IPaymentChannelUpdateRequest {
  id: number, // ID
  code: string, // 渠道标识:wechat=微信支付,alipay=支付宝,balance=余额
  appid: string, // 应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号
  name: string, // 渠道名称
  config: string, // 渠道配置(JSON):商户号/密钥等
  status: number, // 状态:0=停用,1=启用
  sort: number, // 排序
}

export interface IPaymentChannelDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IPaymentChannelQueryResponse {
  currentPage: number, // 当前页码
  data: IPaymentChannelResponse[], // 数据列表
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

export interface IPaymentChannelResponse {
  id: number, // ID
  code: string, // 渠道标识:wechat=微信支付,alipay=支付宝,balance=余额
  appid: string, // 应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号
  name: string, // 渠道名称
  config: string, // 渠道配置(JSON):商户号/密钥等
  status: number, // 状态:0=停用,1=启用
  sort: number, // 排序
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

