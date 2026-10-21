export interface IProductCategoryCreateRequest {
  parentId: number, // 父级ID,0=顶级
  name: string, // 分类名称
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  isRecommend: number, // 首页推荐:0=否,1=是
}

export interface IProductCategoryDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IProductCategoryQueryRequest {
  id?: number, // ID
  parentId?: number, // 父级ID,0=顶级
}

export interface IProductCategoryUpdateRequest {
  id: number, // ID
  parentId: number, // 父级ID,0=顶级
  name: string, // 分类名称
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  isRecommend: number, // 首页推荐:0=否,1=是
}

export interface IProductCategoryDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IProductCategoryQueryResponse {
  currentPage: number, // 当前页码
  data: IProductCategoryResponse[], // 数据列表
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

export interface IProductCategoryResponse {
  id: number, // ID
  parentId: number, // 父级ID,0=顶级
  name: string, // 分类名称
  sort: number, // 排序
  status: number, // 状态:0=禁用,1=启用
  isRecommend: number, // 首页推荐:0=否,1=是
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

