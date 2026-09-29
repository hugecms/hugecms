import React from 'react'

interface PriceTagProps {
  price: number
  symbol?: string
  size?: 'sm' | 'md' | 'lg' | 'xl'
  color?: string
  className?: string
}

export const PriceTag: React.FC<PriceTagProps> = ({
  price,
  symbol = '¥',
  size = 'md',
  color = '#e1251b',
  className = '',
}) => {
  const parts = price.toFixed(2).split('.')
  const integerPart = parts[0]
  const decimalPart = parts[1]

  const sizeClasses = {
    sm: { symbol: 'text-xs', int: 'text-sm font-bold', dec: 'text-xs' },
    md: { symbol: 'text-xs', int: 'text-base font-bold', dec: 'text-xs' },
    lg: { symbol: 'text-sm', int: 'text-xl font-black', dec: 'text-xs' },
    xl: { symbol: 'text-base font-bold', int: 'text-2xl font-black', dec: 'text-sm font-semibold' },
  }[size]

  return (
    <span className={`inline-flex items-baseline ${className}`} style={{ color }}>
      <span className={sizeClasses.symbol}>{symbol}</span>
      <span className={sizeClasses.int}>{integerPart}</span>
      <span className={sizeClasses.dec}>.{decimalPart}</span>
    </span>
  )
}

export default PriceTag
