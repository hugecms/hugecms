import React from 'react'
import { Tag, Button } from 'antd'
import { PlusOutlined, CheckCircleFilled } from '@ant-design/icons'
import { useCartStore } from '../../../stores/cartStore'
import type { ShippingAddress } from '../../../types/trade'

export interface AddressSelectorProps {
  onAddNewAddress?: () => void
}

export const AddressSelector: React.FC<AddressSelectorProps> = ({ onAddNewAddress }) => {
  const { addresses, selectedAddressId, selectAddress } = useCartStore()

  return (
    <div className="bg-white rounded-lg p-5 border border-slate-200">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-sm font-bold text-slate-800">收货人信息</h3>
        {onAddNewAddress && (
          <Button
            type="link"
            size="small"
            icon={<PlusOutlined />}
            onClick={onAddNewAddress}
            className="text-xs text-red-600 p-0"
          >
            新增收货地址
          </Button>
        )}
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
        {addresses.map((addr) => {
          const isSelected = addr.id === selectedAddressId
          return (
            <div
              key={addr.id}
              onClick={() => selectAddress(addr.id)}
              className={`relative p-3 rounded border text-xs cursor-pointer transition ${
                isSelected
                  ? 'border-red-600 bg-red-50/20 ring-1 ring-red-500'
                  : 'border-slate-200 hover:border-slate-300 bg-white'
              }`}
            >
              <div className="flex items-center justify-between mb-1.5">
                <span className="font-bold text-slate-800">{addr.name}</span>
                <span className="text-slate-500">{addr.phone}</span>
              </div>
              <p className="text-slate-600 text-[11px] line-clamp-2 mb-2">
                {addr.province} {addr.city} {addr.district} {addr.detail}
              </p>
              <div className="flex items-center gap-1">
                {addr.isDefault && <Tag color="red" className="text-[10px] m-0">默认</Tag>}
                {addr.tag && <Tag color="blue" className="text-[10px] m-0">{addr.tag}</Tag>}
              </div>
              {isSelected && (
                <CheckCircleFilled className="absolute right-2 bottom-2 text-red-600 text-sm" />
              )}
            </div>
          )
        })}
      </div>
    </div>
  )
}

export default AddressSelector
