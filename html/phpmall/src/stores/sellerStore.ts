import { create } from 'zustand'
import type { DispatchOrderItem, InventoryItem, NewGoodsFormData } from '../types/seller'

interface SellerState {
  // 侧边栏折叠
  isCollapsed: boolean
  toggleCollapse: () => void

  // 发布商品弹窗
  isAddModalOpen: boolean
  openAddModal: () => void
  closeAddModal: () => void

  // 待发货订单
  orders: DispatchOrderItem[]
  dispatchOrder: (sn: string, expressCompany: string, trackingNo: string) => void

  // 库存商品
  inventory: InventoryItem[]
  restockItem: (id: string, addCount: number) => void
  addInventoryItem: (data: NewGoodsFormData) => void

  // 在售总商品数
  onSaleCount: number
}

const initialOrders: DispatchOrderItem[] = [
  {
    sn: 'JD2026092889104',
    orderTime: '2026-09-28 22:58',
    goodsTitle: 'Apple iPhone 16 Pro 256GB 原色钛金属',
    goodsSku: '规格：原色钛金属 / 256GB × 1',
    goodsImage: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=80&q=80',
    payAmount: 7999.0,
    receiverName: '张三',
    receiverPhone: '138****8888',
    receiverAddress: '北京市朝阳区建国路88号 SOHO现代城',
    expressCompany: '京东快递 (211速达)',
    trackingNo: 'JDVB092800123',
    status: 'pending',
  },
  {
    sn: 'JD2026092877112',
    orderTime: '2026-09-28 21:15',
    goodsTitle: 'Sony WH-1000XM5 无线主动降噪头戴耳机',
    goodsSku: '经典黑 / 官方标配 × 1',
    goodsImage: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=80&q=80',
    payAmount: 1899.0,
    receiverName: '王五',
    receiverPhone: '135****6677',
    receiverAddress: '上海市浦东新区陆家嘴环路1000号',
    expressCompany: '顺丰速运 (特快)',
    trackingNo: 'SF188299104',
    status: 'pending',
  },
  {
    sn: 'JD2026092866205',
    orderTime: '2026-09-28 19:40',
    goodsTitle: 'PBT五面热升华客制化机械键盘主题键帽',
    goodsSku: '复古微水泥 × 2',
    goodsImage: 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=80&q=80',
    payAmount: 338.0,
    receiverName: '李四',
    receiverPhone: '136****1234',
    receiverAddress: '广州市天河区珠江新城冼村路11号',
    expressCompany: '中通快递',
    trackingNo: 'ZT776100234',
    status: 'pending',
  },
]

const initialInventory: InventoryItem[] = [
  {
    id: '1',
    name: 'Apple iPhone 16 Pro 256GB 原色钛金属',
    price: 7999.0,
    salesToday: 12,
    stock: 128,
    status: 'normal',
  },
  {
    id: '2',
    name: 'Sony WH-1000XM5 无线降噪耳机 黑色',
    price: 1899.0,
    salesToday: 8,
    stock: 3,
    status: 'warning',
  },
  {
    id: '3',
    name: 'PBT五面热升华客制化机械键盘键帽',
    price: 169.0,
    salesToday: 15,
    stock: 2,
    status: 'warning',
  },
]

export const useSellerStore = create<SellerState>((set) => ({
  isCollapsed: false,
  toggleCollapse: () => set((state) => ({ isCollapsed: !state.isCollapsed })),

  isAddModalOpen: false,
  openAddModal: () => set({ isAddModalOpen: true }),
  closeAddModal: () => set({ isAddModalOpen: false }),

  orders: initialOrders,
  dispatchOrder: (sn, expressCompany, trackingNo) =>
    set((state) => ({
      orders: state.orders.map((o) =>
        o.sn === sn
          ? { ...o, status: 'shipped', expressCompany, trackingNo }
          : o,
      ),
    })),

  inventory: initialInventory,
  restockItem: (id, addCount) =>
    set((state) => ({
      inventory: state.inventory.map((item) => {
        if (item.id === id) {
          const newStock = item.stock + addCount
          return {
            ...item,
            stock: newStock,
            status: newStock > 5 ? 'normal' : 'warning',
          }
        }
        return item
      }),
    })),

  addInventoryItem: (data) =>
    set((state) => ({
      onSaleCount: state.onSaleCount + 1,
      inventory: [
        {
          id: `new_${Date.now()}`,
          name: data.title,
          price: data.price,
          salesToday: 0,
          stock: data.stock,
          isNew: true,
          status: 'normal',
        },
        ...state.inventory,
      ],
    })),

  onSaleCount: 48,
}))
