export interface IWechatMessageCreateRequest {
  appid: string, // 发送所用应用AppID
  userId: number, // 目标用户ID
  templateId: number, // 模板ID
  scene: string, // 业务场景,同模板表scene
  relatedId: number, // 关联业务ID,如订单ID
  openid: string, // 接收者OpenID快照
  content: string, // 推送内容快照(JSON)
  status: number, // 状态:0=待发送,1=成功,2=失败
  error: string, // 失败原因,如:用户未关注/订阅次数用尽
  sentAt: string, // 发送时间
  createTime: string, // 创建时间
}

export interface IWechatMessageDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IWechatMessageQueryRequest {
  id?: number, // ID
  userId?: number, // 目标用户ID
  templateId?: number, // 模板ID
  status?: number, // 状态:0=待发送,1=成功,2=失败
}

export interface IWechatMessageUpdateRequest {
  id: number, // ID
  appid: string, // 发送所用应用AppID
  userId: number, // 目标用户ID
  templateId: number, // 模板ID
  scene: string, // 业务场景,同模板表scene
  relatedId: number, // 关联业务ID,如订单ID
  openid: string, // 接收者OpenID快照
  content: string, // 推送内容快照(JSON)
  status: number, // 状态:0=待发送,1=成功,2=失败
  error: string, // 失败原因,如:用户未关注/订阅次数用尽
  sentAt: string, // 发送时间
  createTime: string, // 创建时间
}

export interface IWechatMessageDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IWechatMessageQueryResponse {
  currentPage: number, // 当前页码
  data: IWechatMessageResponse[], // 数据列表
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

export interface IWechatMessageResponse {
  id: number, // ID
  appid: string, // 发送所用应用AppID
  userId: number, // 目标用户ID
  templateId: number, // 模板ID
  scene: string, // 业务场景,同模板表scene
  relatedId: number, // 关联业务ID,如订单ID
  openid: string, // 接收者OpenID快照
  content: string, // 推送内容快照(JSON)
  status: number, // 状态:0=待发送,1=成功,2=失败
  error: string, // 失败原因,如:用户未关注/订阅次数用尽
  sentAt: string, // 发送时间
  createTime: string, // 创建时间
}

