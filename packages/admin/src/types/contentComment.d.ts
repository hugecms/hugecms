export interface IContentCommentCreateRequest {
  userId: number, // 评论用户ID
  targetType: string, // 目标类型:article=文章;商品评价走product_reviews表
  targetId: number, // 目标ID
  parentId: number, // 父评论ID,0=顶级
  content: string, // 评论内容
  status: number, // 状态:0=待审,1=通过,2=拒绝
  ip: string, // 评论IP
}

export interface IContentCommentDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentCommentQueryRequest {
  id?: number, // ID
  userId?: number, // 评论用户ID
  targetType?: string, // 目标类型:article=文章;商品评价走product_reviews表
  targetId?: number, // 目标ID
}

export interface IContentCommentUpdateRequest {
  id: number, // ID
  userId: number, // 评论用户ID
  targetType: string, // 目标类型:article=文章;商品评价走product_reviews表
  targetId: number, // 目标ID
  parentId: number, // 父评论ID,0=顶级
  content: string, // 评论内容
  status: number, // 状态:0=待审,1=通过,2=拒绝
  ip: string, // 评论IP
}

export interface IContentCommentDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentCommentQueryResponse {
  currentPage: number, // 当前页码
  data: IContentCommentResponse[], // 数据列表
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

export interface IContentCommentResponse {
  id: number, // ID
  userId: number, // 评论用户ID
  targetType: string, // 目标类型:article=文章;商品评价走product_reviews表
  targetId: number, // 目标ID
  parentId: number, // 父评论ID,0=顶级
  content: string, // 评论内容
  status: number, // 状态:0=待审,1=通过,2=拒绝
  ip: string, // 评论IP
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

