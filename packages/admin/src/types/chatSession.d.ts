export interface IChatSessionCreateRequest {
  userId: number, // 发起用户ID
  serverId: number, // 接待客服管理员ID,0=未分配(机器人/排队)
  sourceType: string, // 咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询
  sourceId: number, // 来源ID,商品ID或订单ID
  status: number, // 状态:1=进行中,2=已结束
  userLastReadId: number, // 用户已读消息水位(消息ID),会话级已读
  serverLastReadId: number, // 客服已读消息水位(消息ID)
  rating: number, // 满意度评分:0=未评,1=1星,2=2星,3=3星,4=4星,5=5星
  ratedAt: string, // 评分时间
  assignedAt: string, // 分配客服时间
  closedAt: string, // 结束时间
  closeReason: string, // 结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭
}

export interface IChatSessionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IChatSessionQueryRequest {
  id?: number, // ID
  userId?: number, // 发起用户ID
  serverId?: number, // 接待客服管理员ID,0=未分配(机器人/排队)
  status?: number, // 状态:1=进行中,2=已结束
  updatedAt?: string, // 更新时间
}

export interface IChatSessionUpdateRequest {
  id: number, // ID
  userId: number, // 发起用户ID
  serverId: number, // 接待客服管理员ID,0=未分配(机器人/排队)
  sourceType: string, // 咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询
  sourceId: number, // 来源ID,商品ID或订单ID
  status: number, // 状态:1=进行中,2=已结束
  userLastReadId: number, // 用户已读消息水位(消息ID),会话级已读
  serverLastReadId: number, // 客服已读消息水位(消息ID)
  rating: number, // 满意度评分:0=未评,1=1星,2=2星,3=3星,4=4星,5=5星
  ratedAt: string, // 评分时间
  assignedAt: string, // 分配客服时间
  closedAt: string, // 结束时间
  closeReason: string, // 结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭
}

export interface IChatSessionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IChatSessionQueryResponse {
  currentPage: number, // 当前页码
  data: IChatSessionResponse[], // 数据列表
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

export interface IChatSessionResponse {
  id: number, // ID
  userId: number, // 发起用户ID
  serverId: number, // 接待客服管理员ID,0=未分配(机器人/排队)
  sourceType: string, // 咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询
  sourceId: number, // 来源ID,商品ID或订单ID
  status: number, // 状态:1=进行中,2=已结束
  userLastReadId: number, // 用户已读消息水位(消息ID),会话级已读
  serverLastReadId: number, // 客服已读消息水位(消息ID)
  rating: number, // 满意度评分:0=未评,1=1星,2=2星,3=3星,4=4星,5=5星
  ratedAt: string, // 评分时间
  assignedAt: string, // 分配客服时间
  closedAt: string, // 结束时间
  closeReason: string, // 结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

