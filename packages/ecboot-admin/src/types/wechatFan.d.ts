export interface IWechatFanCreateRequest {
  appid: string, // 公众号AppID
  openid: string, // 公众号OpenID
  unionid: string, // 开放平台UnionID,跨端识别同一用户
  nickname: string, // 昵称快照
  avatar: string, // 头像快照
  subscribed: number, // 关注状态:0=已取关,1=已关注
  subscribedAt: string, // 关注时间
  unsubscribedAt: string, // 取关时间
  subscribeScene: string, // 关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码
  tags: string, // 标签ID列表(JSON),群发圈人依据
  userId: number, // 关联注册用户ID,未注册为空
}

export interface IWechatFanDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IWechatFanQueryRequest {
  id?: number, // ID
  appid?: string, // 公众号AppID
  openid?: string, // 公众号OpenID
  unionid?: string, // 开放平台UnionID,跨端识别同一用户
  userId?: number, // 关联注册用户ID,未注册为空
}

export interface IWechatFanUpdateRequest {
  id: number, // ID
  appid: string, // 公众号AppID
  openid: string, // 公众号OpenID
  unionid: string, // 开放平台UnionID,跨端识别同一用户
  nickname: string, // 昵称快照
  avatar: string, // 头像快照
  subscribed: number, // 关注状态:0=已取关,1=已关注
  subscribedAt: string, // 关注时间
  unsubscribedAt: string, // 取关时间
  subscribeScene: string, // 关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码
  tags: string, // 标签ID列表(JSON),群发圈人依据
  userId: number, // 关联注册用户ID,未注册为空
}

export interface IWechatFanDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IWechatFanQueryResponse {
  currentPage: number, // 当前页码
  data: IWechatFanResponse[], // 数据列表
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

export interface IWechatFanResponse {
  id: number, // ID
  appid: string, // 公众号AppID
  openid: string, // 公众号OpenID
  unionid: string, // 开放平台UnionID,跨端识别同一用户
  nickname: string, // 昵称快照
  avatar: string, // 头像快照
  subscribed: number, // 关注状态:0=已取关,1=已关注
  subscribedAt: string, // 关注时间
  unsubscribedAt: string, // 取关时间
  subscribeScene: string, // 关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码
  tags: string, // 标签ID列表(JSON),群发圈人依据
  userId: number, // 关联注册用户ID,未注册为空
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

