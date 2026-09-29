import React from 'react'

/**
 * 京东经典多快好省徽标 SVG
 */
export const IconDuo: React.FC<{ size?: number; className?: string }> = ({ size = 36, className }) => (
  <svg width={size} height={size} viewBox="0 0 36 36" fill="none" className={className}>
    <circle cx="18" cy="18" r="17" stroke="#e1251b" strokeWidth="2" fill="#fff" />
    <text x="18" y="24" textAnchor="middle" fill="#e1251b" fontSize="16" fontWeight="bold" fontFamily="sans-serif">
      多
    </text>
  </svg>
)

export const IconKuai: React.FC<{ size?: number; className?: string }> = ({ size = 36, className }) => (
  <svg width={size} height={size} viewBox="0 0 36 36" fill="none" className={className}>
    <circle cx="18" cy="18" r="17" stroke="#e1251b" strokeWidth="2" fill="#fff" />
    <text x="18" y="24" textAnchor="middle" fill="#e1251b" fontSize="16" fontWeight="bold" fontFamily="sans-serif">
      快
    </text>
  </svg>
)

export const IconHao: React.FC<{ size?: number; className?: string }> = ({ size = 36, className }) => (
  <svg width={size} height={size} viewBox="0 0 36 36" fill="none" className={className}>
    <circle cx="18" cy="18" r="17" stroke="#e1251b" strokeWidth="2" fill="#fff" />
    <text x="18" y="24" textAnchor="middle" fill="#e1251b" fontSize="16" fontWeight="bold" fontFamily="sans-serif">
      好
    </text>
  </svg>
)

export const IconSheng: React.FC<{ size?: number; className?: string }> = ({ size = 36, className }) => (
  <svg width={size} height={size} viewBox="0 0 36 36" fill="none" className={className}>
    <circle cx="18" cy="18" r="17" stroke="#e1251b" strokeWidth="2" fill="#fff" />
    <text x="18" y="24" textAnchor="middle" fill="#e1251b" fontSize="16" fontWeight="bold" fontFamily="sans-serif">
      省
    </text>
  </svg>
)

/**
 * 京东自营标识 Tag SVG
 */
export const IconJDSelfBadge: React.FC<{ className?: string }> = ({ className }) => (
  <span
    className={className}
    style={{
      background: '#e1251b',
      color: '#fff',
      fontSize: '11px',
      padding: '1px 5px',
      borderRadius: '2px',
      fontWeight: 'bold',
      lineHeight: '1.2',
      display: 'inline-block',
    }}
  >
    自营
  </span>
)
