export interface IMarketingPromotionLadderCreateRequest {
  promotionId: number, // 促销活动ID,关联marketing_promotions
  minAmount: string, // 门槛金额,0=无门槛
  reduceAmount: string, // 满减金额,full_reduce类型必填
  discountRate: string, // 折扣率,如8.5表示85折,discount类型必填
}

export interface IMarketingPromotionLadderDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingPromotionLadderQueryRequest {
  id?: number, // ID
  promotionId?: number, // 促销活动ID,关联marketing_promotions
  minAmount?: string, // 门槛金额,0=无门槛
}

export interface IMarketingPromotionLadderUpdateRequest {
  id: number, // ID
  promotionId: number, // 促销活动ID,关联marketing_promotions
  minAmount: string, // 门槛金额,0=无门槛
  reduceAmount: string, // 满减金额,full_reduce类型必填
  discountRate: string, // 折扣率,如8.5表示85折,discount类型必填
}

export interface IMarketingPromotionLadderDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingPromotionLadderQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingPromotionLadderResponse[], // 数据列表
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

export interface IMarketingPromotionLadderResponse {
  id: number, // ID
  promotionId: number, // 促销活动ID,关联marketing_promotions
  minAmount: string, // 门槛金额,0=无门槛
  reduceAmount: string, // 满减金额,full_reduce类型必填
  discountRate: string, // 折扣率,如8.5表示85折,discount类型必填
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

