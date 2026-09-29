import { request } from './client'
import type { GoodsItem, GoodsFilterParams, CategoryNode } from '../types/goods'
import type { PageResult } from '../types/common'

/**
 * 获取商品列表 (支持分页、筛选、排序)
 */
export async function getGoodsList(params?: GoodsFilterParams): Promise<PageResult<GoodsItem>> {
  return request<PageResult<GoodsItem>>({
    url: '/goods/list',
    method: 'GET',
    params,
  })
}

/**
 * 获取商品详情
 */
export async function getGoodsDetail(id: number | string): Promise<GoodsItem> {
  return request<GoodsItem>({
    url: `/goods/${id}`,
    method: 'GET',
  })
}

/**
 * 获取全部分类树
 */
export async function getCategories(): Promise<CategoryNode[]> {
  return request<CategoryNode[]>({
    url: '/goods/categories',
    method: 'GET',
  })
}

/**
 * 获取秒杀商品列表
 */
export async function getSeckillGoods(): Promise<GoodsItem[]> {
  return request<GoodsItem[]>({
    url: '/goods/seckill',
    method: 'GET',
  })
}
