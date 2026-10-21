export interface IMarketingGroupBuyItemCreateRequest {
  activityId: number, // 拼团活动ID
  skuId: number, // SKU ID
  groupPrice: string, // 该SKU成团价,下单快照进订单项price
}

export interface IMarketingGroupBuyItemDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingGroupBuyItemQueryRequest {
  id?: number, // ID
  activityId?: number, // 拼团活动ID
  skuId?: number, // SKU ID
}

export interface IMarketingGroupBuyItemUpdateRequest {
  id: number, // ID
  activityId: number, // 拼团活动ID
  skuId: number, // SKU ID
  groupPrice: string, // 该SKU成团价,下单快照进订单项price
}

export interface IMarketingGroupBuyItemDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingGroupBuyItemQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingGroupBuyItemResponse[], // 数据列表
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

export interface IMarketingGroupBuyItemResponse {
  id: number, // ID
  activityId: number, // 拼团活动ID
  skuId: number, // SKU ID
  groupPrice: string, // 该SKU成团价,下单快照进订单项price
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

