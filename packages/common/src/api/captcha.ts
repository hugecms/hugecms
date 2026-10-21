import { get } from '@/utils/request'
/** [验证码] 获取验证码图片 */
export function captchaApi(): Promise<any> {
  return get<any>('common/captcha')
}
