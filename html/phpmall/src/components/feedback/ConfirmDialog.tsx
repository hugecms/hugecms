import { Modal } from 'antd'

interface ConfirmDialogProps {
  title: string
  content: string | React.ReactNode
  onOk: () => void | Promise<void>
  onCancel?: () => void
  okText?: string
  cancelText?: string
  danger?: boolean
}

export const showConfirmDialog = ({
  title,
  content,
  onOk,
  onCancel,
  okText = '确定',
  cancelText = '取消',
  danger = false,
}: ConfirmDialogProps) => {
  Modal.confirm({
    title,
    content,
    okText,
    cancelText,
    okButtonProps: danger ? { danger: true } : undefined,
    onOk,
    onCancel,
  })
}

export default showConfirmDialog
