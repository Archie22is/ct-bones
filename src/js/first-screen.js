/* global WOW */
import { on } from 'lib/dom'
import { throttle } from 'lib/utils'

document.addEventListener('DOMContentLoaded', () => {
  let loaded = false

  const wowAnimate = new WOW({
    boxClass: 'has-animation',
    animateClass: 'animated',
    offset: 20,
    mobile: false
  })

  on(
    'scroll',
    throttle(
      () => {
        if (!loaded) {
          wowAnimate.init()

          loaded = true
        }
      }, 100),
    window
  )
})
