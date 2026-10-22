export interface IContentTopicItemCreateRequest {
  topicId: number, // 专题ID
  targetType: string, // 目标类型:article=文章,product=商品
  targetId: number, // 目标ID
  sort: number, // 排序
}

export interface IContentTopicItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentTopicItemQueryRequest {
  id?: number, // ID
  topicId?: number, // 专题ID
  targetType?: string, // 目标类型:article=文章,product=商品
  targetId?: number, // 目标ID
}

export interface IContentTopicItemUpdateRequest {
  id: number, // ID
  topicId: number, // 专题ID
  targetType: string, // 目标类型:article=文章,product=商品
  targetId: number, // 目标ID
  sort: number, // 排序
}

export interface IContentTopicItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentTopicItemQueryResponse {
  currentPage: number, // 当前页码
  data: IContentTopicItemResponse[], // 数据列表
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

export interface IContentTopicItemResponse {
  id: number, // ID
  topicId: number, // 专题ID
  targetType: string, // 目标类型:article=文章,product=商品
  targetId: number, // 目标ID
  sort: number, // 排序
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

