export interface IWechatSubscribeCreateRequest {
  appid: string, // 授权所在小程序AppID
  userId: number, // 用户ID
  templateId: number, // 模板ID,关联wechat_templates
  times: number, // 累计授权次数
  remain: number, // 剩余可发次数,发送成功后扣减
  lastSubscribedAt: string, // 最近授权时间
}

export interface IWechatSubscribeDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IWechatSubscribeQueryRequest {
  id?: number, // ID
  appid?: string, // 授权所在小程序AppID
  userId?: number, // 用户ID
  templateId?: number, // 模板ID,关联wechat_templates
}

export interface IWechatSubscribeUpdateRequest {
  id: number, // ID
  appid: string, // 授权所在小程序AppID
  userId: number, // 用户ID
  templateId: number, // 模板ID,关联wechat_templates
  times: number, // 累计授权次数
  remain: number, // 剩余可发次数,发送成功后扣减
  lastSubscribedAt: string, // 最近授权时间
}

export interface IWechatSubscribeDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IWechatSubscribeQueryResponse {
  currentPage: number, // 当前页码
  data: IWechatSubscribeResponse[], // 数据列表
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

export interface IWechatSubscribeResponse {
  id: number, // ID
  appid: string, // 授权所在小程序AppID
  userId: number, // 用户ID
  templateId: number, // 模板ID,关联wechat_templates
  times: number, // 累计授权次数
  remain: number, // 剩余可发次数,发送成功后扣减
  lastSubscribedAt: string, // 最近授权时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

