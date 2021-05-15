import {
  select,
  selectAll,
  on,
  trigger,
  delegate,
  closest,
  hasClass,
  getData
} from 'lib/dom'
import { debounce } from 'lib/utils'
import { customQuantity } from './woocommerce/quantity'
import { widgetProductCategories } from './woocommerce/widget-product-categories'

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

const getProductImageMarkup = url =>
  `<img class="image__img" src="${url}" alt="">`

const initImageHoverProductCard = () => {
  delegate(
    'mouseover',
    debounce(e => {
      const parentEl = hasClass('.js-product-inner', e.target)
        ? e.target
        : closest('.js-product-inner', e.target)
      const imageHoverEl = parentEl ? select('.js-hover-image', parentEl) : null
      const hoverImageUrl = imageHoverEl
        ? getData('image-url', imageHoverEl)
        : null

      if (hoverImageUrl && !imageHoverEl.innerHTML) {
        imageHoverEl.innerHTML = getProductImageMarkup(hoverImageUrl)
      }
    }, 100),
    '.product__inner',
    document.body
  )
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

document.addEventListener('DOMContentLoaded', () => {
  customQuantity()
  widgetProductCategories()
  initBlocks()
  initImageHoverProductCard()
})
