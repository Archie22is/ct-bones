/* global jQuery, location */
import {
  addClass,
  removeClass,
  hasClass,
  selectAll,
  on,
  trigger
} from 'lib/dom'
import { initQuantityCart } from '../woocommerce/mini-cart'
import { customQuantity } from '../woocommerce/quantity'
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'

const $ = jQuery
const VISIBLE_BODY_CLASS = 'is-mini-cart-opened'
const UPDATING_BODY_CLASS = 'is-mini-cart-updating'
const body = document.body
const triggers = selectAll('.js-minicart-trigger, .ajax_add_to_cart')

const visible = () => addClass(VISIBLE_BODY_CLASS, body)
const hide = () => removeClass(VISIBLE_BODY_CLASS, body)
const loading = () => addClass(UPDATING_BODY_CLASS, body)
const done = () => removeClass(UPDATING_BODY_CLASS, body)
const isCartPage = () => hasClass('woocommerce-cart', body)

const refresh = () => {
  customQuantity()
  initQuantityCart()
}

export default el => {
  const closeButtons = selectAll('.js-mini-cart-close', el)

  $(body).on('added_to_cart', () => {
    trigger('minicart.open', body)
  })

  on(
    'minicart.open',
    () => {
      if (isCartPage()) {
        return
      }

      refresh()
      disableBodyScroll(el)
      visible()
      done()
    },
    body
  )

  on(
    'minicart.close',
    () => {
      loading()
      hide()
      enableBodyScroll(el)
    },
    body
  )

  if (closeButtons) {
    on(
      'click',
      () => {
        trigger('minicart.close', body)
      },
      closeButtons
    )
  }

  on(
    'keydown',
    e => {
      if (e.code === 'Escape' && hasClass(VISIBLE_BODY_CLASS, body)) {
        trigger('minicart.close', body)
      }
    },
    window
  )

  if (triggers) {
    on(
      'click',
      e => {
        e.preventDefault()

        trigger('minicart.open', body)
      },
      triggers
    )
  }

  initQuantityCart()

  $(document.body)
    .on('wc_fragments_loaded wc_fragments_refreshed', refresh)
    .on('wc_cart_emptied', () => {
      location.reload()
    })
}

export { loading, done }
