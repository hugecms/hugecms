export interface IChatFaqCreateRequest {
  category: string, // 分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户
  question: string, // 问题
  answer: string, // 答案
  views: number, // 浏览量
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
}

export interface IChatFaqDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IChatFaqQueryRequest {
  id?: number, // ID
  category?: string, // 分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户
}

export interface IChatFaqUpdateRequest {
  id: number, // ID
  category: string, // 分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户
  question: string, // 问题
  answer: string, // 答案
  views: number, // 浏览量
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
}

export interface IChatFaqDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IChatFaqQueryResponse {
  currentPage: number, // 当前页码
  data: IChatFaqResponse[], // 数据列表
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

export interface IChatFaqResponse {
  id: number, // ID
  category: string, // 分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户
  question: string, // 问题
  answer: string, // 答案
  views: number, // 浏览量
  sort: number, // 排序
  status: number, // 状态:0=隐藏,1=显示
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

