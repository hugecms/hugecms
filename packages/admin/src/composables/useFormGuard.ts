import { onMounted, onUnmounted } from 'vue'
import { onBeforeRouteLeave } from 'vue-router'
import { ElMessageBox } from 'element-plus'
import 'element-plus/es/components/message-box/style/css'

/**
 * 表单离开保护：有未保存修改时，路由切换/关闭标签/刷新页面均提示确认。
 * isDirty 由页面提供（如：对话框打开且表单与初始快照不同）。
 */
export function useFormGuard(
  isDirty: () => boolean,
  message = '当前有未保存的修改，确定离开吗？',
): void {
  onBeforeRouteLeave(async () => {
    if (!isDirty()) {
      return true
    }
    try {
      await ElMessageBox.confirm(message, '提示', {
        type: 'warning',
        confirmButtonText: '离开',
        cancelButtonText: '留下',
      })
      return true
    } catch {
      return false
    }
  })

  const handleBeforeUnload = (event: BeforeUnloadEvent): void => {
    if (isDirty()) {
      event.preventDefault()
      event.returnValue = ''
    }
  }

  onMounted(() => window.addEventListener('beforeunload', handleBeforeUnload))
  onUnmounted(() => window.removeEventListener('beforeunload', handleBeforeUnload))
}
