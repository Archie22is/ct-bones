/* global jQuery */
import { select, selectAll, on, trigger, closest, delegate, addClass, hasClass } from 'lib/dom'
import { throttle } from 'lib/utils'
import { customQuantity } from './woocommerce/quantity'
import { widgetProductCategories } from './woocommerce/widget-product-categories'

const $ = jQuery

const checkoutPageTrigger = select('[data-checkout-page-trigger]')
const checkoutForm = select('form[name="checkout"]')
const woocommerceBlocks = selectAll('[data-woocommerce-block]')

if (checkoutPageTrigger && checkoutForm) {
  on(
    'click',
    e => {
      e.preventDefault()

      trigger('submit', checkoutForm)
    },
    checkoutPageTrigger
  )
}

const initImageHoverProductCard = () => {
  delegate('mouseover', throttle(e => {
    const parentEl = closest('.product__inner', e.target)
    if (hasClass('is-loaded', parentEl)) {
      return
    }

    const imageHoverEl = parentEl ? select('.js-image-hover', parentEl) : null
    const contextEls = parentEl ? parentEl.getElementsByTagName('noscript') : null

    if (!contextEls || !contextEls.length) {
      return false
    }

    const content = contextEls[0].textContent || contextEls[0].innerHTML

    if (imageHoverEl && content) {
      imageHoverEl.innerHTML = content
      addClass('is-loaded', parentEl)
    }
  }, 100), '.product__inner', document.body)
}

const initBlocks = () => {
  if (woocommerceBlocks) {
    woocommerceBlocks.forEach(block => {
      const blockName = block.getAttribute('data-woocommerce-block')
      if (!blockName) {
        return
      }

      require(`./woocommerce-blocks/${blockName}.js`).default(block)
    })
  }
}

on('load', initImageHoverProductCard, window)
$(document.body).on('wc_fragments_loaded', initImageHoverProductCard)

document.addEventListener('DOMContentLoaded', () => {
  customQuantity()
  widgetProductCategories()
  initBlocks()
})
