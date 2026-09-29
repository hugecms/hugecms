import React, { useState } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import { message } from 'antd'
import { AuthLayout } from '../layouts/AuthLayout'
import styles from './login.module.css'

export const Route = createFileRoute('/login')({
  component: LoginPage,
})

function LoginPage() {
  const [activeTab, setActiveTab] = useState<'qrcode' | 'account'>('account')
  const [username, setUsername] = useState('admin')
  const [password, setPassword] = useState('123456')
  const navigate = useNavigate()

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault()
    if (!username || !password) {
      message.error('请输入用户名和密码')
      return
    }
    message.success('登录成功！正在跳转...')
    setTimeout(() => {
      navigate({ to: '/' })
    }, 600)
  }

  return (
    <AuthLayout pageTitle="欢迎登录">
      <div className={styles.loginBannerWrap}>
        <div className={`w ${styles.loginCardBox}`}>
          <div className={styles.loginCard}>
            {/* 切换 Tab */}
            <div className={styles.tabHeader}>
              <button
                type="button"
                className={`${styles.tabBtn} ${activeTab === 'qrcode' ? styles.active : ''}`}
                onClick={() => setActiveTab('qrcode')}
              >
                扫码登录
              </button>
              <button
                type="button"
                className={`${styles.tabBtn} ${activeTab === 'account' ? styles.active : ''}`}
                onClick={() => setActiveTab('account')}
              >
                账户登录
              </button>
            </div>

            {activeTab === 'qrcode' ? (
              <div className={styles.qrcodeArea}>
                <div className={styles.qrcodeBox}>
                  {/* 内联 SVG 登录二维码 */}
                  <svg viewBox="0 0 100 100" className={styles.qrcodeImg}>
                    <rect width="100" height="100" fill="#fff" />
                    <rect x="10" y="10" width="25" height="25" fill="#e1251b" />
                    <rect x="65" y="10" width="25" height="25" fill="#e1251b" />
                    <rect x="10" y="65" width="25" height="25" fill="#e1251b" />
                    <rect x="15" y="15" width="15" height="15" fill="#fff" />
                    <rect x="70" y="15" width="15" height="15" fill="#fff" />
                    <rect x="15" y="70" width="15" height="15" fill="#fff" />
                    <rect x="18" y="18" width="9" height="9" fill="#e1251b" />
                    <rect x="73" y="18" width="9" height="9" fill="#e1251b" />
                    <rect x="18" y="73" width="9" height="9" fill="#e1251b" />
                    <rect x="45" y="20" width="10" height="10" fill="#333" />
                    <rect x="40" y="45" width="20" height="10" fill="#333" />
                    <rect x="20" y="45" width="10" height="10" fill="#333" />
                    <rect x="70" y="45" width="10" height="20" fill="#333" />
                    <rect x="45" y="70" width="20" height="15" fill="#333" />
                  </svg>
                </div>
                <p className={styles.qrcodeDesc}>
                  打开 <span style={{ color: '#e1251b' }}>手机京东</span> 扫描二维码
                </p>
                <button
                  type="button"
                  style={{
                    marginTop: '12px',
                    background: 'none',
                    border: '1px solid #ddd',
                    padding: '4px 12px',
                    fontSize: '12px',
                    cursor: 'pointer',
                  }}
                  onClick={() => {
                    message.success('模拟扫码成功！')
                    navigate({ to: '/' })
                  }}
                >
                  模拟扫码成功
                </button>
              </div>
            ) : (
              <form onSubmit={handleLogin} className={styles.accountForm}>
                <div className={styles.inputGroup}>
                  <input
                    type="text"
                    className={styles.inputField}
                    placeholder="邮箱/用户名/已验证手机"
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                  />
                </div>
                <div className={styles.inputGroup}>
                  <input
                    type="password"
                    className={styles.inputField}
                    placeholder="密码"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                  />
                </div>
                <div className={styles.formExtra}>
                  <label style={{ cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '4px' }}>
                    <input type="checkbox" defaultChecked />
                    <span>自动登录</span>
                  </label>
                  <a href="javascript:;" style={{ color: '#666' }}>忘记密码</a>
                </div>
                <button type="submit" className={styles.btnLogin}>
                  登 录
                </button>
              </form>
            )}

            <div className={styles.cardFooter}>
              <div className={styles.thirdLogin}>
                <a href="javascript:;">QQ</a>
                <span>|</span>
                <a href="javascript:;">微信</a>
              </div>
              <Link to="/register" className={styles.linkRegister}>
                › 立即注册
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AuthLayout>
  )
}

export default LoginPage
