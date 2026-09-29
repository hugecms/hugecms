/**
 * 订单模型、履约流转与支付类型
 */

import type { ShippingAddress } from './trade'

export type OrderStatus =
  | 'unpaid'      // 待付款
  | 'paid'        // 待发货
  | 'shipped'     // 待收货 (已发货)
  | 'completed'   // 已完成
  | 'cancelled'   // 已取消
  | 'refunding'   // 退款中
  | 'refunded'    // 已退款

export interface OrderItem {
  id: string | number
  goodsId: number | string
  goodsTitle: string
  goodsImage: string
  skuId?: string | number
  skuSpecs?: string
  price: number
  quantity: number
  subtotal: number
}

export interface OrderTrackEvent {
  time: string
  title: string
  desc: string
  operator?: string
}

export interface OrderDetail {
  orderId: string
  orderSn: string
  createTime: string
  payTime?: string
  shippingTime?: string
  finishTime?: string
  status: OrderStatus
  statusText: string
  items: OrderItem[]
  totalAmount: number
  discountAmount: number
  freightAmount: number
  payAmount: number
  address: ShippingAddress
  payMethod?: 'wechat' | 'alipay' | 'baitiao' | 'bank'
  invoiceTitle?: string
  trackEvents?: OrderTrackEvent[]
}

export interface OrderQueryParams {
  status?: OrderStatus | 'all'
  keyword?: string
  startDate?: string
  endDate?: string
  page?: number
  pageSize?: number
}
