export interface IContentTagCreateRequest {
  name: string, // 标签名
}

export interface IContentTagDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IContentTagQueryRequest {
  id?: number, // ID
  name?: string, // 标签名
}

export interface IContentTagUpdateRequest {
  id: number, // ID
  name: string, // 标签名
}

export interface IContentTagDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IContentTagQueryResponse {
  currentPage: number, // 当前页码
  data: IContentTagResponse[], // 数据列表
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

export interface IContentTagResponse {
  id: number, // ID
  name: string, // 标签名
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

