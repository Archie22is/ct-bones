/* global jQuery */
import { select, on, addClass, removeClass, trigger, getData } from '../lib/dom'

const body = document.body
const $ = jQuery
export default el => {
  const enableSticky = getData('sticky-header')
  const slideoutTrigger = select('.js-open-mobile-menu', el)
  const navigation = select('.js-sticky', el)
  const scrollUp = 'scroll-up'
  const scrollDown = 'scroll-down'
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

  if (enableSticky && navigation) {
    const top = $(navigation).offset().top
    window.addEventListener('scroll', () => {
      if ($(window).scrollTop() > top) {
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
        removeClass(scrollDown, body)
        removeClass(scrollUp, body)
      }
    })
  }
}
