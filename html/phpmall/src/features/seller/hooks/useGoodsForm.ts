import { Form, message } from 'antd'
import { useSellerStore } from '../../../stores/sellerStore'
import type { NewGoodsFormData } from '../../../types/seller'

export function useGoodsForm() {
  const [form] = Form.useForm<NewGoodsFormData>()
  const { isAddGoodsModalOpen, closeAddGoodsModal, addInventoryGoods } = useSellerStore()

  const handleSubmit = async () => {
    try {
      const values = await form.validateFields()
      addInventoryGoods({
        name: values.title,
        price: values.price,
        stock: values.stock,
      })
      message.success('商品发布成功，已同步至前台商城！')
      form.resetFields()
      closeAddGoodsModal()
    } catch {
      // 验证未通过
    }
  }

  return {
    form,
    isOpen: isAddGoodsModalOpen,
    onClose: closeAddGoodsModal,
    onSubmit: handleSubmit,
  }
}

export default useGoodsForm
