export interface IMarketingPromotionCreateRequest {
  name: string, // 活动名称
  type: string, // 类型:full_reduce=满减,discount=折扣
  rangeType: string, // 适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
  scopes?: IMarketingPromotionScopeEntity[], // 适用范围列表,rangeType=all 时不传
  ladders: IMarketingPromotionLadderEntity[], // 优惠档位列表,至少一档
  startTime: string, // 活动开始时间
  endTime: string, // 活动结束时间
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingPromotionDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingPromotionQueryRequest {
  id?: number, // ID
}

export interface IMarketingPromotionUpdateRequest {
  id: number, // ID
  name: string, // 活动名称
  type: string, // 类型:full_reduce=满减,discount=折扣
  rangeType: string, // 适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
  scopes?: IMarketingPromotionScopeEntity[], // 适用范围列表,rangeType=all 时不传
  ladders: IMarketingPromotionLadderEntity[], // 优惠档位列表,至少一档
  startTime: string, // 活动开始时间
  endTime: string, // 活动结束时间
  status: number, // 状态:0=停用,1=启用
}

export interface IMarketingPromotionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingPromotionQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingPromotionResponse[], // 数据列表
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

export interface IMarketingPromotionResponse {
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

export interface IMarketingPromotionScopeEntity {
  id?: number, // ID
  promotionId?: number, // 促销活动ID
  scopeType: string, // 范围类型:category=分类,brand=品牌,product=商品
  targetId: number, // 目标ID,按scopeType指向分类/品牌/商品
  createdAt?: string, // 创建时间
  updatedAt?: string, // 更新时间
}

export interface IMarketingPromotionLadderEntity {
  id?: number, // ID
  promotionId?: number, // 促销活动ID
  minAmount: string, // 门槛金额,0=无门槛
  reduceAmount?: string | null, // 满减金额,full_reduce类型必填
  discountRate?: string | null, // 折扣率,如8.5表示85折,discount类型必填
  createdAt?: string, // 创建时间
  updatedAt?: string, // 更新时间
}
