export interface IContentArticleCreateRequest {
  categoryId: number, // 栏目ID
  title: string, // 标题
  slug: string, // 文章标识,用于URL
  summary: string, // 摘要
  cover: string, // 封面图
  productIds: string, // 关联商品ID列表(JSON数组),种草文章挂商品购买入口
  authorId: number, // 作者管理员ID
  author: string, // 作者名称快照
  isTop: number, // 置顶:0=否,1=是
  isRecommend: number, // 推荐:0=否,1=是
  status: number, // 状态:0=草稿,1=发布,2=下架
  views: number, // 浏览量
  publishedAt: string, // 发布时间
}

export interface IContentArticleDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentArticleQueryRequest {
  id?: number, // ID
  categoryId?: number, // 栏目ID
  slug?: string, // 文章标识,用于URL
  status?: number, // 状态:0=草稿,1=发布,2=下架
  publishedAt?: string, // 发布时间
}

export interface IContentArticleUpdateRequest {
  id: number, // ID
  categoryId: number, // 栏目ID
  title: string, // 标题
  slug: string, // 文章标识,用于URL
  summary: string, // 摘要
  cover: string, // 封面图
  productIds: string, // 关联商品ID列表(JSON数组),种草文章挂商品购买入口
  authorId: number, // 作者管理员ID
  author: string, // 作者名称快照
  isTop: number, // 置顶:0=否,1=是
  isRecommend: number, // 推荐:0=否,1=是
  status: number, // 状态:0=草稿,1=发布,2=下架
  views: number, // 浏览量
  publishedAt: string, // 发布时间
}

export interface IContentArticleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentArticleQueryResponse {
  currentPage: number, // 当前页码
  data: IContentArticleResponse[], // 数据列表
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

export interface IContentArticleResponse {
  id: number, // ID
  categoryId: number, // 栏目ID
  title: string, // 标题
  slug: string, // 文章标识,用于URL
  summary: string, // 摘要
  cover: string, // 封面图
  productIds: string, // 关联商品ID列表(JSON数组),种草文章挂商品购买入口
  authorId: number, // 作者管理员ID
  author: string, // 作者名称快照
  isTop: number, // 置顶:0=否,1=是
  isRecommend: number, // 推荐:0=否,1=是
  status: number, // 状态:0=草稿,1=发布,2=下架
  views: number, // 浏览量
  publishedAt: string, // 发布时间
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
  deletedAt: string, // 删除时间
}

