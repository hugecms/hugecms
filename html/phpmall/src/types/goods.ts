/**
 * 商品、SKU、SPU、类目模型
 */

export interface SkuSpecValue {
  name: string
  img?: string
}

export interface SkuItem {
  id: string | number
  skuCode: string
  title: string
  price: number
  marketPrice?: number
  stock: number
  specs?: Record<string, string>
  image: string
}

export interface GoodsItem {
  id: number | string
  title: string
  slogan?: string
  brand: string
  price: number
  originalPrice?: number
  sales: number
  comments: string | number
  rating: string
  image: string
  images?: string[]
  tags: string[]
  shop: string
  inStock: boolean
  category?: string
  skus?: SkuItem[]
}

export interface CategoryNode {
  id: string | number
  name: string
  icon?: string
  children?: CategorySubNode[]
}

export interface CategorySubNode {
  id: string | number
  name: string
  tags?: string[]
  children?: {
    id: string | number
    name: string
  }[]
}

export interface GoodsFilterParams {
  q?: string
  categoryId?: string | number
  brand?: string
  minPrice?: number
  maxPrice?: number
  sort?: 'default' | 'sales' | 'comments' | 'price_asc' | 'price_desc'
  page?: number
  pageSize?: number
}
