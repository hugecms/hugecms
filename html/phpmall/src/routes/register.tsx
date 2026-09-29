import React, { useState } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import { message } from 'antd'
import { AuthLayout } from '../layouts/AuthLayout'
import styles from './register.module.css'

export const Route = createFileRoute('/register')({
  component: RegisterPage,
})

function RegisterPage() {
  const [step, setStep] = useState<1 | 2 | 3>(1)
  const [phone, setPhone] = useState('')
  const [code, setCode] = useState('')
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [codeCounting, setCodeCounting] = useState(0)
  const [isSuccess, setIsSuccess] = useState(false)
  const navigate = useNavigate()

  // 密码强度计算
  const getPasswordStrength = (pwd: string) => {
    if (!pwd) return { level: 0, text: '未输入' }
    let score = 0
    if (pwd.length >= 8) score++
    if (/[A-Z]/.test(pwd) && /[a-z]/.test(pwd)) score++
    if (/[0-9]/.test(pwd)) score++
    if (/[^A-Za-z0-9]/.test(pwd)) score++

    if (score <= 1) return { level: 1, text: '弱' }
    if (score <= 3) return { level: 2, text: '中' }
    return { level: 3, text: '强' }
  }

  const strength = getPasswordStrength(password)

  const handleSendCode = () => {
    if (!phone || phone.length < 11) {
      message.error('请输入有效的 11 位手机号码')
      return
    }
    message.success('验证码已发送至您的手机 (验证码: 888666)')
    setCodeCounting(60)
    const timer = setInterval(() => {
      setCodeCounting((prev) => {
        if (prev <= 1) {
          clearInterval(timer)
          return 0
        }
        return prev - 1
      })
    }, 1000)
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    if (!phone) {
      message.error('请输入手机号')
      return
    }
    if (!username) {
      message.error('请设置用户名')
      return
    }
    if (!password || password.length < 6) {
      message.error('密码不能少于 6 位')
      return
    }
    setIsSuccess(true)
    setStep(3)
    message.success('注册成功！已为您送达 188 元新人大礼包！')
  }

  return (
    <AuthLayout pageTitle="欢迎注册">
      <main className={`w ${styles.registerMain}`}>
        {/* 三步流程指示条 */}
        <div className={styles.stepBar}>
          <div className={`${styles.stepNode} ${step >= 1 ? styles.active : ''}`}>
            <span className={styles.stepNum}>1</span>
            <span className={styles.stepText}>验证手机号</span>
          </div>
          <div className={`${styles.stepLine} ${step >= 2 ? styles.active : ''}`} />
          <div className={`${styles.stepNode} ${step >= 2 ? styles.active : ''}`}>
            <span className={styles.stepNum}>2</span>
            <span className={styles.stepText}>填写账号信息</span>
          </div>
          <div className={`${styles.stepLine} ${step >= 3 ? styles.active : ''}`} />
          <div className={`${styles.stepNode} ${step >= 3 ? styles.active : ''}`}>
            <span className={styles.stepNum}>3</span>
            <span className={styles.stepText}>注册成功</span>
          </div>
        </div>

        {!isSuccess ? (
          <form onSubmit={handleSubmit} className={styles.formBox}>
            <div className={styles.formRow}>
              <div className={styles.inputGroup}>
                <div className={styles.inputPrepend}>中国 +86</div>
                <input
                  type="text"
                  className={styles.inputField}
                  placeholder="建议使用常用手机号"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                />
              </div>
            </div>

            <div className={styles.formRow}>
              <div className={styles.inputGroup}>
                <input
                  type="text"
                  className={styles.inputField}
                  placeholder="请输入手机短信验证码"
                  value={code}
                  onChange={(e) => setCode(e.target.value)}
                />
                <button
                  type="button"
                  className={styles.btnSendCode}
                  disabled={codeCounting > 0}
                  onClick={handleSendCode}
                >
                  {codeCounting > 0 ? `${codeCounting}s 后重发` : '获取验证码'}
                </button>
              </div>
            </div>

            <div className={styles.formRow}>
              <div className={styles.inputGroup}>
                <input
                  type="text"
                  className={styles.inputField}
                  placeholder="设置用户名 (4-20个字符)"
                  value={username}
                  onChange={(e) => {
                    setUsername(e.target.value)
                    if (step === 1 && e.target.value) setStep(2)
                  }}
                />
              </div>
            </div>

            <div className={styles.formRow}>
              <div className={styles.inputGroup}>
                <input
                  type="password"
                  className={styles.inputField}
                  placeholder="设置登录密码 (建议包含字母和数字)"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                />
              </div>
              {password && (
                <div className={styles.strengthBar}>
                  <span>密码强度：</span>
                  <div className={styles.strengthIndicators}>
                    <span className={`${styles.strengthDot} ${strength.level >= 1 ? styles.weak : ''}`} />
                    <span className={`${styles.strengthDot} ${strength.level >= 2 ? styles.medium : ''}`} />
                    <span className={`${styles.strengthDot} ${strength.level >= 3 ? styles.strong : ''}`} />
                  </div>
                  <span>{strength.text}</span>
                </div>
              )}
            </div>

            <button type="submit" className={styles.btnRegisterSubmit}>
              同意协议并注册
            </button>

            <div style={{ marginTop: '16px', fontSize: '12px', color: '#999', textAlign: 'center' }}>
              点击注册即代表您同意 <a href="javascript:;" style={{ color: '#005ea7' }}>《京东用户注册协议》</a> 和 <a href="javascript:;" style={{ color: '#005ea7' }}>《隐私政策》</a>
            </div>
          </form>
        ) : (
          <div className={styles.successCard}>
            <div style={{ fontSize: '48px', marginBottom: '12px' }}>🎉</div>
            <h3 style={{ fontSize: '18px', fontWeight: 'bold', color: '#333' }}>恭喜您，注册成功！</h3>
            <p style={{ fontSize: '12px', color: '#666', marginTop: '6px' }}>
              您的京东会员账号已激活，已自动为您开通极速支付与全自营售后保障。
            </p>

            <div className={styles.giftBox}>
              <h4 style={{ color: '#e1251b', fontSize: '13px', fontWeight: 'bold', marginBottom: '4px' }}>
                🎁 新人专属 188 元全品类礼包已到账
              </h4>
              <p style={{ fontSize: '11px', color: '#666' }}>
                含全场满减券、免运费券及 PLUS 会员专属立减神券，可在「我的优惠券」查收使用。
              </p>
            </div>

            <div style={{ display: 'flex', gap: '12px', justifyContent: 'center' }}>
              <button
                type="button"
                onClick={() => navigate({ to: '/' })}
                style={{
                  background: '#e1251b',
                  color: '#fff',
                  border: 'none',
                  padding: '10px 24px',
                  borderRadius: '4px',
                  cursor: 'pointer',
                  fontWeight: 'bold',
                }}
              >
                立即去商城选购
              </button>
              <button
                type="button"
                onClick={() => navigate({ to: '/user' })}
                style={{
                  background: '#fff',
                  color: '#666',
                  border: '1px solid #ddd',
                  padding: '10px 20px',
                  borderRadius: '4px',
                  cursor: 'pointer',
                }}
              >
                进入个人中心
              </button>
            </div>
          </div>
        )}
      </main>
    </AuthLayout>
  )
}

export default RegisterPage
