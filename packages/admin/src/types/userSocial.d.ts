export interface IUserSocialCreateRequest {
  userId: number, // 用户ID
  platform: string, // 平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub
  openId: string, // 第三方OpenID
  unionId: string, // 第三方UnionID,如微信多应用互通
  nickname: string, // 第三方昵称快照
  avatar: string, // 第三方头像快照
}

export interface IUserSocialDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IUserSocialQueryRequest {
  id?: number, // ID
  userId?: number, // 用户ID
  platform?: string, // 平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub
  openId?: string, // 第三方OpenID
}

export interface IUserSocialUpdateRequest {
  id: number, // ID
  userId: number, // 用户ID
  platform: string, // 平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub
  openId: string, // 第三方OpenID
  unionId: string, // 第三方UnionID,如微信多应用互通
  nickname: string, // 第三方昵称快照
  avatar: string, // 第三方头像快照
}

export interface IUserSocialDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserSocialQueryResponse {
  currentPage: number, // 当前页码
  data: IUserSocialResponse[], // 数据列表
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

export interface IUserSocialResponse {
  id: number, // ID
  userId: number, // 用户ID
  platform: string, // 平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub
  openId: string, // 第三方OpenID
  unionId: string, // 第三方UnionID,如微信多应用互通
  nickname: string, // 第三方昵称快照
  avatar: string, // 第三方头像快照
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

