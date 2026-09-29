import dayjs from 'dayjs'

/**
 * 格式化金额，添加 ¥ 符号并保留指定位数小数
 * @param amount 金额数值
 * @param decimals 小数位数，默认为 2
 */
export function formatPrice(amount: number | string | undefined | null, decimals = 2): string {
  if (amount === undefined || amount === null || isNaN(Number(amount))) {
    return '¥0.00'
  }
  const num = Number(amount)
  return `¥${num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`
}

/**
 * 格式化精简金额（不带千分位符号，适合卡片展示）
 */
export function formatPriceSimple(amount: number | string | undefined | null): string {
  if (amount === undefined || amount === null || isNaN(Number(amount))) {
    return '0.00'
  }
  return Number(amount).toFixed(2)
}

/**
 * 格式化日期时间
 * @param date 日期字符串、时间戳或 Date 对象
 * @param pattern 格式化模板，默认 'YYYY-MM-DD HH:mm:ss'
 */
export function formatDate(
  date: string | number | Date | undefined | null,
  pattern = 'YYYY-MM-DD HH:mm:ss'
): string {
  if (!date) return ''
  return dayjs(date).format(pattern)
}

/**
 * 格式化数字统计（如评价数、销量：12000 -> 1.2万）
 */
export function formatCount(count: number | string | undefined | null): string {
  if (!count) return '0'
  const num = Number(count)
  if (isNaN(num)) return String(count)
  if (num >= 10000) {
    return `${(num / 10000).toFixed(1).replace(/\.0$/, '')}万+`
  }
  return String(num)
}
