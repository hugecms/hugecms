import { request } from './client'
import type { OrderDetail, OrderQueryParams } from '../types/order'
import type { PageResult } from '../types/common'
import type { ShippingAddress } from '../types/trade'

export interface CreateOrderParams {
  items: { goodsId: number | string; skuId?: number | string; quantity: number }[]
  address: ShippingAddress
  payMethod: 'online' | 'cod' | 'baitiao'
  invoiceTitle?: string
  buyerRemark?: string
}

export interface CreateOrderResult {
  orderId: string
  orderSn: string
  payAmount: number
  payUrl?: string
}

/**
 * 提交并创建订单
 */
export async function createOrder(data: CreateOrderParams): Promise<CreateOrderResult> {
  return request<CreateOrderResult>({
    url: '/order/create',
    method: 'POST',
    data,
  })
}

/**
 * 查询买家订单列表
 */
export async function getOrderList(params?: OrderQueryParams): Promise<PageResult<OrderDetail>> {
  return request<PageResult<OrderDetail>>({
    url: '/order/list',
    method: 'GET',
    params,
  })
}

/**
 * 获取订单详情
 */
export async function getOrderDetail(orderId: string): Promise<OrderDetail> {
  return request<OrderDetail>({
    url: `/order/${orderId}`,
    method: 'GET',
  })
}

/**
 * 支付订单
 */
export async function payOrder(orderId: string, channel: string): Promise<{ success: boolean; paySn: string }> {
  return request<{ success: boolean; paySn: string }>({
    url: `/order/${orderId}/pay`,
    method: 'POST',
    data: { channel },
  })
}

/**
 * 取消订单
 */
export async function cancelOrder(orderId: string, reason?: string): Promise<void> {
  return request<void>({
    url: `/order/${orderId}/cancel`,
    method: 'POST',
    data: { reason },
  })
}
