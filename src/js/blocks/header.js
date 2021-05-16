/* global jQuery */
import { select, on, addClass, removeClass, trigger, getData } from '../lib/dom'

const body = document.body
const $ = jQuery
export default el => {
  const slideoutTrigger = select('.js-open-mobile-menu', el)
  const enableSticky = getData('sticky-header', el)
  const scrollUp = 'scroll-up'
  const scrollDown = 'scroll-down'
  const fixedHeader = 'is-fixed-header'
  const maxHeight = el.offsetHeight
  const headerSticky = select('.js-sticky-header', el)
  let lastScroll = 0
  if (slideoutTrigger) {
    on(
      'click',
      () => {
        trigger('slideout.visible', body)
      },
      slideoutTrigger
    )
  }
  if (enableSticky) {
    window.addEventListener('scroll', () => {
      if ($(window).scrollTop() > maxHeight) {
        addClass(fixedHeader, body)
        el.setAttribute('style', `height: ${maxHeight}px`)
        const stickyHeight = headerSticky.offsetHeight
        headerSticky.setAttribute('style', `height: ${stickyHeight}px`)
        addClass(enableSticky, headerSticky)
        const currentScroll = window.pageYOffset
        if (currentScroll <= 0) {
          removeClass(scrollUp, body)
          return
        }

        if (
          currentScroll > lastScroll &&
          !body.classList.contains(scrollDown)
        ) {
          // down
          removeClass(scrollUp, body)
          addClass(scrollDown, body)
        } else if (
          currentScroll < lastScroll &&
          body.classList.contains(scrollDown)
        ) {
          // up
          removeClass(scrollDown, body)
          addClass(scrollUp, body)
        }
        lastScroll = currentScroll
      } else {
        removeClass(scrollUp, body)
        removeClass(fixedHeader, body)
        el.style.height = null
        headerSticky.style.height = null
      }
    })
  }
}
