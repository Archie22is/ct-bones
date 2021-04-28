/* global jQuery */
import { select, selectAll, on, trigger } from 'lib/dom'
import { customQuantity } from './woocommerce/quantity'
import { widgetProductCategories } from './woocommerce/widget-product-categories'
import { singleProduct } from './woocommerce/single-product'

const $ = jQuery

const addToCardEls = selectAll('.js-add-to-card')
const checkoutPageTrigger = select('[data-checkout-page-trigger]')
const checkoutForm = select('form[name="checkout"]')
const woocommerceBlocks = selectAll('[data-woocommerce-block]')

const init = () => {
  if (addToCardEls) {
    const addToCards = $(addToCardEls).find(
      '.add_to_cart_button.ajax_add_to_cart'
    )

    $(addToCards).each(function () {
      const cartSVG =
        '<span><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path d="M2.75 12.5c-.965 0-1.75.785-1.75 1.75S1.785 16 2.75 16s1.75-.785 1.75-1.75-.785-1.75-1.75-1.75zm0 2.5a.75.75 0 010-1.5.75.75 0 010 1.5zm8.5-2.5c-.965 0-1.75.785-1.75 1.75S10.285 16 11.25 16 13 15.215 13 14.25s-.785-1.75-1.75-1.75zm0 2.5a.75.75 0 010-1.5.75.75 0 010 1.5zm2.121-13l-.302 2H-.074l1.118 8.036h11.913l1.038-7.463L14.231 3H17V2h-3.629zm-.445 3l-.139 1H1.213l-.139-1h11.852zM1.914 11.036L1.353 7h11.295l-.561 4.036H1.914z"/></svg>\n</span>'
      $(this).text('')
      $(this).prepend(cartSVG)
    })
  }
}

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
