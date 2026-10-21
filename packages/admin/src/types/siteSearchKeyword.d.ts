export interface ISiteSearchKeywordCreateRequest {
  keyword: string, // 搜索词
  searchCount: number, // 搜索次数
  resultCount: number, // 最近一次结果数,0=无结果
  lastSearchedAt: string, // 最后搜索时间
  isHot: number, // 人工推荐热词:0=否,1=是
  status: number, // 状态:0=屏蔽,1=正常
}

export interface ISiteSearchKeywordDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface ISiteSearchKeywordQueryRequest {
  id?: number, // ID
  keyword?: string, // 搜索词
  searchCount?: number, // 搜索次数
}

export interface ISiteSearchKeywordUpdateRequest {
  id: number, // ID
  keyword: string, // 搜索词
  searchCount: number, // 搜索次数
  resultCount: number, // 最近一次结果数,0=无结果
  lastSearchedAt: string, // 最后搜索时间
  isHot: number, // 人工推荐热词:0=否,1=是
  status: number, // 状态:0=屏蔽,1=正常
}

export interface ISiteSearchKeywordDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface ISiteSearchKeywordQueryResponse {
  currentPage: number, // 当前页码
  data: ISiteSearchKeywordResponse[], // 数据列表
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

export interface ISiteSearchKeywordResponse {
  id: number, // ID
  keyword: string, // 搜索词
  searchCount: number, // 搜索次数
  resultCount: number, // 最近一次结果数,0=无结果
  lastSearchedAt: string, // 最后搜索时间
  isHot: number, // 人工推荐热词:0=否,1=是
  status: number, // 状态:0=屏蔽,1=正常
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

