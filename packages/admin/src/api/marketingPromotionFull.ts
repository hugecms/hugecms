import { post, get } from '@/utils/request'
import type { IMarketingPromotionFullCreateRequest, IMarketingPromotionFullResponse, IMarketingPromotionFullUpdateRequest, IMarketingPromotionFullDestroyRequest, IMarketingPromotionFullDestroyResponse } from '@/types/marketingPromotionFull'

/** [促销活动(主子表)] 创建促销活动(含范围与档位) */
export function marketingPromotionFullCreateApi(data: IMarketingPromotionFullCreateRequest): Promise<IMarketingPromotionFullResponse> {
  return post<IMarketingPromotionFullResponse>('admin/marketingPromotionFull/create', data)
}

/** [促销活动(主子表)] 更新促销活动(整体替换范围与档位) */
export function marketingPromotionFullUpdateApi(data: IMarketingPromotionFullUpdateRequest): Promise<IMarketingPromotionFullResponse> {
  return post<IMarketingPromotionFullResponse>('admin/marketingPromotionFull/update', data)
}

/** [促销活动(主子表)] 促销活动详情(含范围与档位) */
export function marketingPromotionFullShowApi(id: number): Promise<IMarketingPromotionFullResponse> {
  return get<IMarketingPromotionFullResponse>('admin/marketingPromotionFull/show', { params: { id } })
}

/** [促销活动(主子表)] 删除促销活动(级联清理范围与档位) */
export function marketingPromotionFullDestroyApi(data: IMarketingPromotionFullDestroyRequest): Promise<IMarketingPromotionFullDestroyResponse> {
  return post<IMarketingPromotionFullDestroyResponse>('admin/marketingPromotionFull/destroy', data)
}
