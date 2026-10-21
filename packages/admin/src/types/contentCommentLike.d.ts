export interface IContentCommentLikeCreateRequest {
  commentId: number, // 评论ID
  userId: number, // 点赞用户ID
  createTime: string, // 创建时间
}

export interface IContentCommentLikeDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentCommentLikeQueryRequest {
  commentId?: number, // 评论ID
  userId?: number, // 点赞用户ID
}

export interface IContentCommentLikeUpdateRequest {
  commentId: number, // 评论ID
  userId: number, // 点赞用户ID
  createTime: string, // 创建时间
}

export interface IContentCommentLikeDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentCommentLikeQueryResponse {
  currentPage: number, // 当前页码
  data: IContentCommentLikeResponse[], // 数据列表
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

export interface IContentCommentLikeResponse {
  commentId: number, // 评论ID
  userId: number, // 点赞用户ID
  createTime: string, // 创建时间
}

