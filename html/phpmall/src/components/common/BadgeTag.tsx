import React from 'react'

interface BadgeTagProps {
  type: 'zy' | 'seckill' | 'promo' | 'plus' | 'custom'
  text?: string
  children?: React.ReactNode
  className?: string
}

export const BadgeTag: React.FC<BadgeTagProps> = ({
  type,
  text,
  children,
  className = '',
}) => {
  const content = text || children

  switch (type) {
    case 'zy':
      return (
        <span
          className={`inline-block px-1 py-0.2 text-[10px] leading-tight font-medium rounded text-white bg-[#e1251b] mr-1 ${className}`}
        >
          {content || '自营'}
        </span>
      )
    case 'plus':
      return (
        <span
          className={`inline-block px-1.5 py-0.2 text-[10px] leading-tight font-bold rounded text-[#2b2b2b] bg-[#ffde94] border border-[#d4b065] mr-1 ${className}`}
        >
          {content || 'PLUS'}
        </span>
      )
    case 'seckill':
      return (
        <span
          className={`inline-block px-1.5 py-0.2 text-[10px] leading-tight font-medium rounded text-[#e1251b] bg-[#fff1f0] border border-[#ffa39e] mr-1 ${className}`}
        >
          {content || '秒杀'}
        </span>
      )
    case 'promo':
    default:
      return (
        <span
          className={`inline-block px-1.5 py-0.2 text-[10px] leading-tight font-normal rounded text-[#e1251b] border border-[#e1251b] mr-1 ${className}`}
        >
          {content}
        </span>
      )
  }
}

export default BadgeTag
