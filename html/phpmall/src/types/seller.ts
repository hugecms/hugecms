/**
 * 商家端核心类型定义
 */

export interface MetricItem {
  id: string
  title: string
  value: string
  unit?: string
  compareText: string
  compareType: 'up' | 'down' | 'neutral'
}

export interface TodoAlert {
  id: string
  type: 'red' | 'yellow' | 'blue'
  icon: string
  text: string
  actionText: string
  actionKey: 'deliver' | 'refund' | 'activity'
}

export interface DispatchOrderItem {
  sn: string
  orderTime: string
  goodsTitle: string
  goodsSku: string
  goodsImage: string
  payAmount: number
  receiverName: string
  receiverPhone: string
  receiverAddress: string
  expressCompany: string
  trackingNo: string
  status: 'pending' | 'shipped'
}

export interface InventoryItem {
  id: string
  name: string
  price: number
  salesToday: number
  stock: number
  isNew?: boolean
  status: 'normal' | 'warning'
}

export interface NewGoodsFormData {
  title: string
  category: string
  price: number
  stock: number
  logisticsMode: string
  imageUrl: string
}
