/* global jQuery */
import { select, selectAll, on, trigger } from 'lib/dom'
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
  const productEls = selectAll('.products > .product')
  if (productEls && productEls.length) {
    productEls.forEach(productEl => {
      const contextEls = productEl.getElementsByTagName('noscript')
      let loaded = false
      let loading = false
      if (!contextEls || !contextEls.length) {
        return false
      }
      const content = contextEls[0].textContent || contextEls[0].innerHTML
      const parentEl = select('.js-image-hover', productEl)
      on(
        'mouseover',
        () => {
          if (!loaded && !loading) {
            loading = true
            parentEl.innerHTML = content
            loaded = true
            loading = false
          }
        },
        productEl
      )
    })
  }
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
  singleProduct()
  init()
  initBlocks()
})
