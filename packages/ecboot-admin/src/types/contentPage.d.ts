export interface IContentPageCreateRequest {
  title: string, // 页面标题
  slug: string, // 页面标识,如:about
  content: string, // 页面内容
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  status: number, // 状态:0=隐藏,1=显示
}

export interface IContentPageDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentPageQueryRequest {
  id?: number, // ID
  slug?: string, // 页面标识,如:about
}

export interface IContentPageUpdateRequest {
  id: number, // ID
  title: string, // 页面标题
  slug: string, // 页面标识,如:about
  content: string, // 页面内容
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  status: number, // 状态:0=隐藏,1=显示
}

export interface IContentPageDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentPageQueryResponse {
  currentPage: number, // 当前页码
  data: IContentPageResponse[], // 数据列表
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

export interface IContentPageResponse {
  id: number, // ID
  title: string, // 页面标题
  slug: string, // 页面标识,如:about
  content: string, // 页面内容
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  status: number, // 状态:0=隐藏,1=显示
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

