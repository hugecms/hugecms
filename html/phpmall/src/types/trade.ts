/**
 * 交易与购物车结算核心模型定义
 */

export interface CartItem {
  id: string
  goodsId: string
  title: string
  sku: string
  imageUrl: string
  price: number
  quantity: number
  selected: boolean
  isSelfOperated?: boolean
  shopName: string
  discountTip?: string
}

export interface ShippingAddress {
  id: string
  name: string
  phone: string
  tag?: string
  province: string
  city: string
  district: string
  detailAddress: string
  isDefault?: boolean
}

export type PaymentMethod = 'baitiao' | 'wechat' | 'alipay' | 'bank' | 'b2b'

export interface OrderPaymentInfo {
  orderSn: string
  totalAmount: number
  createTime: string
  receiverName: string
  receiverPhone: string
  receiverAddress: string
  items: {
    title: string
    sku: string
    price: number
    quantity: number
    imageUrl: string
  }[]
}
