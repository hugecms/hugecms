export interface IContentTopicCreateRequest {
  title: string, // 专题标题
  slug: string, // 专题标识,用于URL
  cover: string, // 专题头图
  description: string, // 专题简介
  content: string, // 自定义内容(富文本)
  template: string, // 模板标识,空=默认模板
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  startTime: string, // 上线时间,空=立即
  endTime: string, // 下线时间,空=不限
  sort: number, // 排序
  status: number, // 状态:0=下线,1=上线
}

export interface IContentTopicDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentTopicQueryRequest {
  id?: number, // ID
  slug?: string, // 专题标识,用于URL
}

export interface IContentTopicUpdateRequest {
  id: number, // ID
  title: string, // 专题标题
  slug: string, // 专题标识,用于URL
  cover: string, // 专题头图
  description: string, // 专题简介
  content: string, // 自定义内容(富文本)
  template: string, // 模板标识,空=默认模板
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  startTime: string, // 上线时间,空=立即
  endTime: string, // 下线时间,空=不限
  sort: number, // 排序
  status: number, // 状态:0=下线,1=上线
}

export interface IContentTopicDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentTopicQueryResponse {
  currentPage: number, // 当前页码
  data: IContentTopicResponse[], // 数据列表
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

export interface IContentTopicResponse {
  id: number, // ID
  title: string, // 专题标题
  slug: string, // 专题标识,用于URL
  cover: string, // 专题头图
  description: string, // 专题简介
  content: string, // 自定义内容(富文本)
  template: string, // 模板标识,空=默认模板
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  startTime: string, // 上线时间,空=立即
  endTime: string, // 下线时间,空=不限
  sort: number, // 排序
  status: number, // 状态:0=下线,1=上线
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

