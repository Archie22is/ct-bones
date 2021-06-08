/* global jQuery,codetotConfig */
import { selectAll, getData } from 'lib/dom'
import { loading, done } from '../woocommerce-blocks/mini-cart'
const $ = jQuery

const initQuantityCart = callbackFunction => {
  const inputEls = selectAll('.mini-cart-sidebar .qty')

  inputEls.forEach(inputEl => {
    let input = $(inputEl)
    let cartItemKey = getData('cart_item_key', inputEl) || ''

    input.on('input', function () {
      let inputVal = Number($(this).val() || 0)

      // Valid quantity.
      if (inputVal < 1 || isNaN(inputVal)) {
        return ''
      }

      let data = {
        action: 'update_quantity_in_mini_cart',
        nonce: codetotConfig.ajax.nonce,
        key: cartItemKey,
        qty: inputVal
      }
      $.ajax({
        url: codetotConfig.ajax.url,
        data: data,
        type: 'POST',
        // dataType: 'json',
        beforeSend: function (response) {
          loading()
        },
        complete: function (response) {
          $(document).trigger('quantity_updated')
        },
        success: function (result) {
          $('body').trigger('wc_fragment_refresh')
          done()
        }
      })
    })
  })
}

export { initQuantityCart }
