import { request } from './client'
import type {
  MetricItem,
  DispatchOrderItem,
  InventoryItem,
  NewGoodsFormData,
} from '../types/seller'
import type { PageResult } from '../types/common'

export interface DispatchParams {
  expressCompany: string
  trackingNo: string
}

/**
 * 获取商家大屏核心指标
 */
export async function getSellerMetrics(): Promise<MetricItem[]> {
  return request<MetricItem[]>({
    url: '/seller/metrics',
    method: 'GET',
  })
}

/**
 * 获取待发货订单履约列表
 */
export async function getSellerOrders(params?: {
  status?: string
  page?: number
  pageSize?: number
}): Promise<PageResult<DispatchOrderItem>> {
  return request<PageResult<DispatchOrderItem>>({
    url: '/seller/orders',
    method: 'GET',
    params,
  })
}

/**
 * 订单发货
 */
export async function dispatchOrder(
  orderSn: string,
  params: DispatchParams
): Promise<{ success: boolean; message: string }> {
  return request<{ success: boolean; message: string }>({
    url: `/seller/orders/${orderSn}/dispatch`,
    method: 'POST',
    data: params,
  })
}

/**
 * 商家发布新商品
 */
export async function addGoods(
  goodsData: NewGoodsFormData
): Promise<{ id: string; success: boolean }> {
  return request<{ id: string; success: boolean }>({
    url: '/seller/goods',
    method: 'POST',
    data: goodsData,
  })
}

/**
 * 获取商家库存商品列表
 */
export async function getSellerGoods(params?: {
  keyword?: string
  page?: number
  pageSize?: number
}): Promise<PageResult<InventoryItem>> {
  return request<PageResult<InventoryItem>>({
    url: '/seller/goods/inventory',
    method: 'GET',
    params,
  })
}
