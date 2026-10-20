import { describe, it, expect } from 'vitest'

import { mount } from '@vue/test-utils'
import App from '../App.vue'

describe('App', () => {
  it('挂载成功并渲染 el-config-provider', () => {
    const wrapper = mount(App)
    expect(wrapper.findComponent({ name: 'ElConfigProvider' }).exists()).toBe(true)
  })
})
