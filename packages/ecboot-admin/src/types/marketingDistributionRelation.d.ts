export interface IMarketingDistributionRelationCreateRequest {
  userId: number, // 被绑定用户ID(下单人)
  distributorId: number, // 分销员用户ID
  level: number, // 层级:1=一级(直接邀请),2=二级(间接)
  createTime: string, // 绑定时间
}

export interface IMarketingDistributionRelationDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingDistributionRelationQueryRequest {
  id?: number, // ID
  userId?: number, // 被绑定用户ID(下单人)
  distributorId?: number, // 分销员用户ID
}

export interface IMarketingDistributionRelationUpdateRequest {
  id: number, // ID
  userId: number, // 被绑定用户ID(下单人)
  distributorId: number, // 分销员用户ID
  level: number, // 层级:1=一级(直接邀请),2=二级(间接)
  createTime: string, // 绑定时间
}

export interface IMarketingDistributionRelationDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingDistributionRelationQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingDistributionRelationResponse[], // 数据列表
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

export interface IMarketingDistributionRelationResponse {
  id: number, // ID
  userId: number, // 被绑定用户ID(下单人)
  distributorId: number, // 分销员用户ID
  level: number, // 层级:1=一级(直接邀请),2=二级(间接)
  createTime: string, // 绑定时间
}

