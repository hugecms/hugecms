import { create } from 'zustand'
import type { CartItem, ShippingAddress, PaymentMethod } from '../types/trade'

interface CartState {
  // 购物车列表
  items: CartItem[]
  toggleSelect: (id: string) => void
  toggleSelectAll: (selected: boolean) => void
  updateQuantity: (id: string, delta: number) => void
  removeItem: (id: string) => void
  removeSelected: () => void

  // 结算地址
  addresses: ShippingAddress[]
  selectedAddressId: string
  selectAddress: (id: string) => void
  addAddress: (addr: Omit<ShippingAddress, 'id'>) => void

  // 支付方式
  paymentMethod: PaymentMethod
  setPaymentMethod: (method: PaymentMethod) => void

  // 计算属性
  getSelectedItems: () => CartItem[]
  getSelectedCount: () => number
  getTotalPrice: () => number
  getDiscountAmount: () => number
  getFinalPayAmount: () => number
}

const initialCartItems: CartItem[] = [
  {
    id: 'c101',
    goodsId: '101',
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
    sku: '规格：原色钛金属 / 256GB',
    imageUrl: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=160&q=80',
    price: 7999.0,
    quantity: 1,
    selected: true,
    isSelfOperated: true,
    shopName: '京东自营旗舰店',
  },
  {
    id: 'c102',
    goodsId: '102',
    title: 'Sony WH-1000XM5 无线头戴主动降噪耳机 黑色',
    sku: '经典黑 / 官方标配',
    imageUrl: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=160&q=80',
    price: 1899.0,
    quantity: 1,
    selected: true,
    isSelfOperated: true,
    shopName: '京东自营旗舰店',
  },
  {
    id: 'c103',
    goodsId: '103',
    title: 'PBT五面热升华客制化机械键盘主题键帽 128键',
    sku: '复古微水泥',
    imageUrl: 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=160&q=80',
    price: 169.0,
    quantity: 2,
    selected: true,
    isSelfOperated: false,
    shopName: '客制化外设潮玩专营店',
  },
]

const initialAddresses: ShippingAddress[] = [
  {
    id: 'addr_1',
    name: '张三',
    phone: '138****8888',
    tag: '默认',
    province: '北京市',
    city: '朝阳区',
    district: '建国门外大街',
    detailAddress: '建国路88号 SOHO现代城 A座 1802室',
    isDefault: true,
  },
  {
    id: 'addr_2',
    name: '张三 (公司)',
    phone: '138****8888',
    tag: '公司',
    province: '北京市',
    city: '海淀区',
    district: '中关村软件园二期',
    detailAddress: '京东全球总部大厦 A座前台',
    isDefault: false,
  },
]

export const useCartStore = create<CartState>((set, get) => ({
  items: initialCartItems,

  toggleSelect: (id) =>
    set((state) => ({
      items: state.items.map((item) =>
        item.id === id ? { ...item, selected: !item.selected } : item,
      ),
    })),

  toggleSelectAll: (selected) =>
    set((state) => ({
      items: state.items.map((item) => ({ ...item, selected })),
    })),

  updateQuantity: (id, delta) =>
    set((state) => ({
      items: state.items.map((item) => {
        if (item.id === id) {
          const newQty = Math.max(1, item.quantity + delta)
          return { ...item, quantity: newQty }
        }
        return item
      }),
    })),

  removeItem: (id) =>
    set((state) => ({
      items: state.items.filter((item) => item.id !== id),
    })),

  removeSelected: () =>
    set((state) => ({
      items: state.items.filter((item) => !item.selected),
    })),

  addresses: initialAddresses,
  selectedAddressId: 'addr_1',
  selectAddress: (id) => set({ selectedAddressId: id }),
  addAddress: (addr) =>
    set((state) => ({
      addresses: [...state.addresses, { ...addr, id: `addr_${Date.now()}` }],
    })),

  paymentMethod: 'baitiao',
  setPaymentMethod: (method) => set({ paymentMethod: method }),

  getSelectedItems: () => get().items.filter((item) => item.selected),

  getSelectedCount: () =>
    get()
      .items.filter((item) => item.selected)
      .reduce((sum, item) => sum + item.quantity, 0),

  getTotalPrice: () =>
    get()
      .items.filter((item) => item.selected)
      .reduce((sum, item) => sum + item.price * item.quantity, 0),

  getDiscountAmount: () => {
    const total = get().getTotalPrice()
    return total >= 300 ? 50 : 0
  },

  getFinalPayAmount: () => {
    const total = get().getTotalPrice()
    const discount = get().getDiscountAmount()
    return Math.max(0, total - discount)
  },
}))
