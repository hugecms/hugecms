export interface IMarketingPromotionFullCreateRequest {
  name: string, // 活动名称
  type: string, // 类型:full_reduce=满减,discount=折扣
  rangeType: string, // 适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
  scopes?: IMarketingPromotionScopeEntity[], // 适用范围列表,rangeType=all 时不传
  ladders: IMarketingPromotionLadderEntity[], // 优惠档位列表,至少一档
  startTime: string, // 活动开始时间
  endTime: string, // 活动结束时间
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingPromotionFullDestroyRequest {
  ids: number[], // 促销活动ID集合
}

export interface IMarketingPromotionFullUpdateRequest {
  id: number, // 促销活动ID(query 参数)
  name: string, // 活动名称
  type: string, // 类型:full_reduce=满减,discount=折扣
  rangeType: string, // 适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
  scopes?: IMarketingPromotionScopeEntity[], // 适用范围列表,rangeType=all 时不传
  ladders: IMarketingPromotionLadderEntity[], // 优惠档位列表,至少一档
  startTime: string, // 活动开始时间
  endTime: string, // 活动结束时间
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingPromotionFullDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingPromotionFullResponse {
  id: number, // ID
  name: string, // 活动名称
  type: string, // 类型:full_reduce=满减,discount=折扣
  rangeType: string, // 适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
  scopes: IMarketingPromotionScopeEntity[], // 适用范围列表,rangeType=all 时为空
  ladders: IMarketingPromotionLadderEntity[], // 优惠档位列表
  startTime: string, // 活动开始时间
  endTime: string, // 活动结束时间
  status: number, // 状态:0=停用,1=启用
  createdAt: string, // 创建时间
  updatedAt: string, // 更新时间
}

