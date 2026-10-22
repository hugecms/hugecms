export interface IMarketingShareRecordCreateRequest {
  sharerUserId: number, // 分享者用户ID
  spuId: number, // 分享对象商品SPU ID,NULL=整店/页面分享
  shareChannel: number, // 分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群
  sceneValue: string, // 小程序场景值,落地追踪
  shareCode: string, // 使用的推广码,关联users.share_code
  createTime: string, // 分享时间,归因窗口起点
}

export interface IMarketingShareRecordDestroyRequest {
  ids: number[], // 数据ID集合
}

export interface IMarketingShareRecordQueryRequest {
  id?: number, // ID
  sharerUserId?: number, // 分享者用户ID
  spuId?: number, // 分享对象商品SPU ID,NULL=整店/页面分享
  createTime?: string, // 分享时间,归因窗口起点
}

export interface IMarketingShareRecordUpdateRequest {
  id: number, // ID
  sharerUserId: number, // 分享者用户ID
  spuId: number, // 分享对象商品SPU ID,NULL=整店/页面分享
  shareChannel: number, // 分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群
  sceneValue: string, // 小程序场景值,落地追踪
  shareCode: string, // 使用的推广码,关联users.share_code
  createTime: string, // 分享时间,归因窗口起点
}

export interface IMarketingShareRecordDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IMarketingShareRecordQueryResponse {
  currentPage: number, // 当前页码
  data: IMarketingShareRecordResponse[], // 数据列表
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

export interface IMarketingShareRecordResponse {
  id: number, // ID
  sharerUserId: number, // 分享者用户ID
  spuId: number, // 分享对象商品SPU ID,NULL=整店/页面分享
  shareChannel: number, // 分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群
  sceneValue: string, // 小程序场景值,落地追踪
  shareCode: string, // 使用的推广码,关联users.share_code
  createTime: string, // 分享时间,归因窗口起点
}

