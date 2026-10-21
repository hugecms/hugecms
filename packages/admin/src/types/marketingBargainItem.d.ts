export interface IMarketingBargainItemCreateRequest {
  activityId: number, // 砍价活动ID
  skuId: number, // SKU ID
  originalPrice: string, // 起始价(发起时售价口径)
  floorPrice: string, // 底价(砍到底价即可下单;floor&lt;=original 应用校验)
  maxCutCount: number, // 最大帮砍刀数,0=不限,金额收敛到底价
  config: string, // 玩法参数(随机递减算法边界/首刀上限等,应用层约定)
}

export interface IMarketingBargainItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingBargainItemQueryRequest {
  id?: number, // ID
  activityId?: number, // 砍价活动ID
  skuId?: number, // SKU ID
}

export interface IMarketingBargainItemUpdateRequest {
  id: number, // ID
  activityId: number, // 砍价活动ID
  skuId: number, // SKU ID
  originalPrice: string, // 起始价(发起时售价口径)
  floorPrice: string, // 底价(砍到底价即可下单;floor&lt;=original 应用校验)
  maxCutCount: number, // 最大帮砍刀数,0=不限,金额收敛到底价
  config: string, // 玩法参数(随机递减算法边界/首刀上限等,应用层约定)
}

export interface IMarketingBargainItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingBargainItemQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingBargainItemResponse[], // 数据列表
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

export interface IMarketingBargainItemResponse {
  id: number, // ID
  activityId: number, // 砍价活动ID
  skuId: number, // SKU ID
  originalPrice: string, // 起始价(发起时售价口径)
  floorPrice: string, // 底价(砍到底价即可下单;floor&lt;=original 应用校验)
  maxCutCount: number, // 最大帮砍刀数,0=不限,金额收敛到底价
  config: string, // 玩法参数(随机递减算法边界/首刀上限等,应用层约定)
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

