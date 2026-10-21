export interface IWechatTemplateCreateRequest {
  appid: string, // 所属应用AppID,公众号或小程序
  scene: string, // 业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等
  type: number, // 模板类型:1=公众号模板消息,2=小程序订阅消息
  templateId: string, // 微信模板ID
  name: string, // 模板名称,运营识别用
  fields: string, // 字段映射(JSON):业务字段到模板参数的映射
  page: string, // 点击跳转页面路径,如:pages/order/detail?id=1
  sendCount: number, // 发送次数
  status: number, // 状态:0=停用,1=启用
}

export interface IWechatTemplateDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IWechatTemplateQueryRequest {
  id?: number, // ID
  appid?: string, // 所属应用AppID,公众号或小程序
  scene?: string, // 业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等
  type?: number, // 模板类型:1=公众号模板消息,2=小程序订阅消息
}

export interface IWechatTemplateUpdateRequest {
  id: number, // ID
  appid: string, // 所属应用AppID,公众号或小程序
  scene: string, // 业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等
  type: number, // 模板类型:1=公众号模板消息,2=小程序订阅消息
  templateId: string, // 微信模板ID
  name: string, // 模板名称,运营识别用
  fields: string, // 字段映射(JSON):业务字段到模板参数的映射
  page: string, // 点击跳转页面路径,如:pages/order/detail?id=1
  sendCount: number, // 发送次数
  status: number, // 状态:0=停用,1=启用
}

export interface IWechatTemplateDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IWechatTemplateQueryResponse {
  currentPage: number, // 当前页码
  data: IWechatTemplateResponse[], // 数据列表
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

export interface IWechatTemplateResponse {
  id: number, // ID
  appid: string, // 所属应用AppID,公众号或小程序
  scene: string, // 业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等
  type: number, // 模板类型:1=公众号模板消息,2=小程序订阅消息
  templateId: string, // 微信模板ID
  name: string, // 模板名称,运营识别用
  fields: string, // 字段映射(JSON):业务字段到模板参数的映射
  page: string, // 点击跳转页面路径,如:pages/order/detail?id=1
  sendCount: number, // 发送次数
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

