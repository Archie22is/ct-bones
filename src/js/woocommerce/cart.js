/* global wc_add_to_cart_params, jQuery */
import { hasClass, select, on, closest } from 'lib/dom'

const $ = jQuery

const body = document.body

const initAddToCartSingleProduct = () => {
  const isSingleProductPage = hasClass('single-product', body)

  if (!isSingleProductPage) {
    return false
  }

  const addToCartButton = select('.single_add_to_cart_button')

  if (!addToCartButton) {
    return false
  }

  const initAddToCartSingleProductAction = e => {
    e.preventDefault()

    const formCartEl = closest('form.cart', e.target)
    const productId = formCartEl && select('input[name="product_id"]', formCartEl)
      ? select('input[name="product_id"]', formCartEl).value
      : null
    const quantity = formCartEl && select('input[name="quantity"]', formCartEl)
      ? select('input[name="quantity"]', formCartEl).value
      : 1
    const hasVariation =
    (formCartEl && select('input[name="variation_id"]', formCartEl)) || false
    const variationId = hasVariation
      ? select('input[name="variation_id"]', formCartEl).value
      : null

    if (!productId || (hasVariation && !variationId)) {
      return
    }

    let data = {
      action: 'codetot_woocommerce_ajax_add_to_cart',
      product_id: productId,
      quantity: quantity,
      variation_id: variationId
    }

    if (hasVariation) {
      data.variation_id = variationId
    }

    $.ajax({
      type: 'POST',
      url: wc_add_to_cart_params.ajax_url,
      data: data,
      beforeSend: function (response) {
        $(formCartEl).removeClass('added').addClass('loading')
      },
      complete: function (response) {
        $(formCartEl).addClass('added').removeClass('loading')
      },
      success: function (response) {
        console.log(response)

        if (response.error & response.product_url) {
          window.location = response.product_url
        } else {
          $(document.body).trigger('added_to_cart')
        }
      }
    })
  }

  // on(
  //   'click',
  //   initAddToCartSingleProductAction,
  //   addToCartButton
  // )
}

export {
  initAddToCartSingleProduct
}
