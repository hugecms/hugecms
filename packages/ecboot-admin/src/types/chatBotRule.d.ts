export interface IChatBotRuleCreateRequest {
  keyword: string, // 命中关键词,消息包含即触发
  replyType: number, // 回复类型:1=文本,2=FAQ,3=转人工
  content: string, // 回复内容,文本回复时用
  faqId: number, // FAQ ID,FAQ回复时用
  hitCount: number, // 命中次数,运营优化依据
  sort: number, // 排序,小者优先匹配
  status: number, // 状态:0=停用,1=启用
}

export interface IChatBotRuleDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IChatBotRuleQueryRequest {
  id?: number, // ID
}

export interface IChatBotRuleUpdateRequest {
  id: number, // ID
  keyword: string, // 命中关键词,消息包含即触发
  replyType: number, // 回复类型:1=文本,2=FAQ,3=转人工
  content: string, // 回复内容,文本回复时用
  faqId: number, // FAQ ID,FAQ回复时用
  hitCount: number, // 命中次数,运营优化依据
  sort: number, // 排序,小者优先匹配
  status: number, // 状态:0=停用,1=启用
}

export interface IChatBotRuleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IChatBotRuleQueryResponse {
  currentPage: number, // 当前页码
  data: IChatBotRuleResponse[], // 数据列表
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

export interface IChatBotRuleResponse {
  id: number, // ID
  keyword: string, // 命中关键词,消息包含即触发
  replyType: number, // 回复类型:1=文本,2=FAQ,3=转人工
  content: string, // 回复内容,文本回复时用
  faqId: number, // FAQ ID,FAQ回复时用
  hitCount: number, // 命中次数,运营优化依据
  sort: number, // 排序,小者优先匹配
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

