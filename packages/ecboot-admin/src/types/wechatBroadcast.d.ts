export interface IWechatBroadcastCreateRequest {
  appid: string, // 群发公众号AppID
  type: string, // 类型:news=图文,text=文本,image=图片
  content: string, // 群发内容(JSON),图文为素材列表
  target: string, // 目标:all=全部粉丝,或标签ID列表
  sendCount: number, // 送达数
  status: number, // 状态:0=待发送,1=已发送,2=发送失败
  msgId: string, // 微信群发消息ID,回拉发送结果用
  userType: string, // 操作账户类型:admin,system
  userId: number, // 操作人ID
  sentAt: string, // 发送时间
}

export interface IWechatBroadcastDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IWechatBroadcastQueryRequest {
  id?: number, // ID
}

export interface IWechatBroadcastUpdateRequest {
  id: number, // ID
  appid: string, // 群发公众号AppID
  type: string, // 类型:news=图文,text=文本,image=图片
  content: string, // 群发内容(JSON),图文为素材列表
  target: string, // 目标:all=全部粉丝,或标签ID列表
  sendCount: number, // 送达数
  status: number, // 状态:0=待发送,1=已发送,2=发送失败
  msgId: string, // 微信群发消息ID,回拉发送结果用
  userType: string, // 操作账户类型:admin,system
  userId: number, // 操作人ID
  sentAt: string, // 发送时间
}

export interface IWechatBroadcastDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IWechatBroadcastQueryResponse {
  currentPage: number, // 当前页码
  data: IWechatBroadcastResponse[], // 数据列表
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

export interface IWechatBroadcastResponse {
  id: number, // ID
  appid: string, // 群发公众号AppID
  type: string, // 类型:news=图文,text=文本,image=图片
  content: string, // 群发内容(JSON),图文为素材列表
  target: string, // 目标:all=全部粉丝,或标签ID列表
  sendCount: number, // 送达数
  status: number, // 状态:0=待发送,1=已发送,2=发送失败
  msgId: string, // 微信群发消息ID,回拉发送结果用
  userType: string, // 操作账户类型:admin,system
  userId: number, // 操作人ID
  sentAt: string, // 发送时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

