import { clsx, type ClassValue } from 'clsx'
import { twMerge } from 'tailwind-merge'

/**
 * 组合 Tailwind CSS 与 clsx 类名，防止样式冲突
 */
export function cn(...inputs: ClassValue[]): string {
  return twMerge(clsx(inputs))
}
