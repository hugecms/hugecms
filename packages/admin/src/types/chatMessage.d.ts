export interface IChatMessageCreateRequest {
  sessionId: number, // 会话ID
  senderType: string, // 发送者类型:user=用户,admin=客服,bot=机器人,system=系统
  senderId: number, // 发送者ID,system为0
  type: string, // 消息类型:text=文本,image=图片,product=商品卡片,order=订单卡片,system=系统事件
  content: string, // 消息内容,文本或卡片数据(JSON)
  createTime: string, // 创建时间,消息只追加不修改
}

export interface IChatMessageDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IChatMessageQueryRequest {
  id?: number, // ID
  sessionId?: number, // 会话ID
}

export interface IChatMessageUpdateRequest {
  id: number, // ID
  sessionId: number, // 会话ID
  senderType: string, // 发送者类型:user=用户,admin=客服,bot=机器人,system=系统
  senderId: number, // 发送者ID,system为0
  type: string, // 消息类型:text=文本,image=图片,product=商品卡片,order=订单卡片,system=系统事件
  content: string, // 消息内容,文本或卡片数据(JSON)
  createTime: string, // 创建时间,消息只追加不修改
}

export interface IChatMessageDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IChatMessageQueryResponse {
  currentPage: number, // 当前页码
  data: IChatMessageResponse[], // 数据列表
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

export interface IChatMessageResponse {
  id: number, // ID
  sessionId: number, // 会话ID
  senderType: string, // 发送者类型:user=用户,admin=客服,bot=机器人,system=系统
  senderId: number, // 发送者ID,system为0
  type: string, // 消息类型:text=文本,image=图片,product=商品卡片,order=订单卡片,system=系统事件
  content: string, // 消息内容,文本或卡片数据(JSON)
  createTime: string, // 创建时间,消息只追加不修改
}

