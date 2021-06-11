/* global jQuery,codetotConfig,wc_add_to_cart_variation_params */
import { select, on, getData, addClass, removeClass, delegate } from 'lib/dom'
import { minusBtn, plusBtn } from '../woocommerce/quantity'
import { pipe } from 'lib/utils'
import 'whatwg-fetch'
import ProductGallery from './product-gallery'
const $ = jQuery

const BODY_CLASS = 'is-quick-view-modal-visible'
const LOADING_CLASS = 'is-loading'
const body = document.body

const parseJSON = response => {
  return response.json()
}

export default el => {
  const closeEl = select('.js-close-modal', el)
  const sliderWrapperEl = select('.js-slider-wrapper', el)
  const contentWrapperEl = select('.js-content', el)
  const openModal = addClass(BODY_CLASS)
  const closeModal = () => removeClass(BODY_CLASS, body)
  const activateLoader = () => addClass(LOADING_CLASS, el)
  const deactivateLoader = () => removeClass(LOADING_CLASS, el)

  let currentPostId = null

  const clearExistingHtml = () => {
    sliderWrapperEl.innerHTML = ''
    contentWrapperEl.innerHTML = ''

    return el
  }

  const initVariation = () => {
    // eslint-disable-next-line camelcase,valid-typeof
    if (undefined !== typeof wc_add_to_cart_variation_params) {
      const $variationForm = $(el).find('.variations_form')
      let slideFirstImage = $(el).find(
        '.quick-view-modal__slider-item:eq(0) img'
      )
      $variationForm.on('found_variation', function (event, variation) {
        if (
          variation.image &&
          variation.image.src &&
          variation.image.src.length > 1
        ) {
          slideFirstImage.attr('src', variation.image.full_src)
          slideFirstImage.wc_set_variation_attr('src', variation.image.src)
          slideFirstImage.wc_set_variation_attr('height', variation.image.src_h)
          slideFirstImage.wc_set_variation_attr('width', variation.image.src_w)
          slideFirstImage.wc_set_variation_attr(
            'srcset',
            variation.image.srcset
          )
          slideFirstImage.wc_set_variation_attr('sizes', variation.image.sizes)
          slideFirstImage.wc_set_variation_attr('title', variation.image.title)
          slideFirstImage.wc_set_variation_attr('alt', variation.image.alt)
          slideFirstImage.wc_set_variation_attr(
            'data-src',
            variation.image.full_src
          )
          slideFirstImage.wc_set_variation_attr(
            'data-large_image',
            variation.image.full_src
          )
          slideFirstImage.wc_set_variation_attr(
            'data-large_image_width',
            variation.image.full_src_w
          )
          slideFirstImage.wc_set_variation_attr(
            'data-large_image_height',
            variation.image.full_src_h
          )
          slideFirstImage.wc_set_variation_attr(
            'href',
            variation.image.full_src
          )
        }
      })
    }

    return el
  }

  const initSlider = () => {
    const productGalleryEl = select(
      '[data-woocommerce-block="product-gallery"]',
      el
    )
    // eslint-disable-next-line no-unused-vars
    let productGalleryInstance = null

    if (productGalleryEl) {
      productGalleryInstance = new ProductGallery(productGalleryEl)
    }

    return el
  }

  const fetchData = () => {
    window
      .fetch(codetotConfig.ajax.url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Cache-Control': 'no-cache'
        },
        body: new URLSearchParams({
          action: 'shop_quick_view',
          nonce: codetotConfig.ajax.nonce,
          product_id: currentPostId
        })
      })
      .then(parseJSON)
      .then(function (data) {
        if (data.sliderHtml) {
          sliderWrapperEl.innerHTML = data.sliderHtml
        }
        if (data.contentHtml) {
          contentWrapperEl.innerHTML = data.contentHtml
        }

        pipe(
          initSlider,
          initVariation,
          customQuantity,
          initChangeVariation,
          deactivateLoader,
          initAddToCart
        )(el)
      })
      .catch(function (error) {
        console.log('request failed', error)
      })
  }

  const hasClass = (element, cls) => {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1
  }

  const initAddToCart = () => {
    let form = select('.quick-view-modal form.cart')
    if (!form || hasClass(form, 'grouped_form')) return

    on(
      'submit',
      e => {
        e.preventDefault()
        let button = $(document)
          .find('.quick-view-modal')
          .find('button[name="add-to-cart"]')
        let resetVariations = $(document)
          .find('.quick-view-modal')
          .find('.reset_variations')
        let productQty = select('input[name=quantity]', form)
        let productId = select('input[name=product_id]', form)
        let variationId = select('input[name=variation_id]', form)
        let productQtyValue = Number(productQty.value || 0)
        let productIdValue = 0
        let variationIdValue = 0
        if (variationId) variationIdValue = Number(variationId.value || 0)
        if (productId) {
          productIdValue = productId.value
        } else {
          productIdValue = select('button[name=add-to-cart]', form).value
        }
        productIdValue = Number(productIdValue || 0)
        let dataFetch = {
          action: 'shop_quick_view_add_to_cart',
          product_id: productIdValue,
          product_sku: '',
          quantity: productQtyValue,
          variation_id: variationIdValue
        }
        window
          .fetch(codetotConfig.ajax.url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Cache-Control': 'no-cache'
            },
            body: new URLSearchParams(dataFetch)
          })
          .then(parseJSON)
          .then(button.attr('disabled', 'disabled'))
          .then(function (data) {
            $(document.body).trigger('added_to_cart', [
              data.fragments,
              data.cart_hash,
              button
            ])
          })
          .then(button.removeAttr('disabled'))
          .then(resetVariations.trigger('click'))
          .then(closeModal())
          .then(initSlider)
          .then(deactivateLoader)
          .catch(function (error) {
            console.log('request failed', error)
          })
      },
      form
    )
  }

  const initChangeVariation = () => {
    let modalQuickView = $(document).find('.quick-view-modal')
    let $variationForm = modalQuickView.find('.variations_form')
    if (!$variationForm.length) {
      return
    }

    // eslint-disable-next-line camelcase,valid-typeof
    if (undefined !== typeof wc_add_to_cart_variation_params) {
      $variationForm.wc_variation_form()
      $variationForm.find('.variations select').change()
    }
  }

  const customQuantity = () => {
    let quantity = el.querySelectorAll('.quantity')
    if (!quantity.length) {
      return
    }

    // Foreach.
    quantity.forEach(function (ele) {
      // Input.
      var input = ele.querySelector('input.qty')
      if (!input) {
        return
      }

      // Add class ajax-ready on first load.
      input.classList.add('ajax-ready')

      // Append Minus button before Input.
      if (!ele.querySelector('.product-qty[data-qty="minus"]')) {
        ele.insertBefore(minusBtn(), input)
      }

      // Append Plus button after Input.
      if (!ele.querySelector('.product-qty[data-qty="plus"]')) {
        ele.appendChild(plusBtn())
      }

      // Vars.
      var cart = ele.closest('form.cart')
      var buttons = ele.querySelectorAll('.product-qty')
      var maxInput = Number(input.getAttribute('max'))
      // eslint-disable-next-line no-undef
      var eventChange = new Event('change')

      // Get product info.
      var productInfo = cart ? cart.querySelector('.additional-product') : false
      var inStock = productInfo
        ? productInfo.getAttribute('data-in_stock')
        : 'no'
      var outStock = productInfo
        ? productInfo.getAttribute('data-out_of_stock')
        : 'Out of stock'
      var notEnough = productInfo
        ? productInfo.getAttribute('data-not_enough')
        : ''
      var quantityValid = productInfo
        ? productInfo.getAttribute('data-valid_quantity')
        : ''

      // Check valid quantity.
      input.addEventListener('change', function () {
        var inputVal = input.value
        var inCartQty = productInfo ? Number(productInfo.value || 0) : 0
        var min = Number(input.getAttribute('min') || 0)
        var ajaxReady = function () {
          input.classList.remove('ajax-ready')
        }

        // When quantity updated.
        input.classList.add('ajax-ready')

        // Valid quantity.
        if (inputVal < min || isNaN(inputVal)) {
          // eslint-disable-next-line no-undef
          alert(quantityValid)
          ajaxReady()
          return
        }

        // Stock status.
        if (inStock === 'yes') {
          // Out of stock.
          if (maxInput && inCartQty === maxInput) {
            // eslint-disable-next-line no-undef
            alert(outStock)
            ajaxReady()
            return
          }

          // Not enough quantity.
          if (maxInput && +inputVal + +inCartQty > maxInput) {
            // eslint-disable-next-line no-undef
            alert(notEnough)
            ajaxReady()
          }
        }
      })

      // Minus & Plus button click.
      for (var i = 0, j = buttons.length; i < j; i++) {
        buttons[i].onclick = function () {
          // Variables.
          var t = this
          var current = Number(input.value || 0)
          var step = Number(input.getAttribute('step') || 1)
          var min = Number(input.getAttribute('min') || 0)
          var max = Number(input.getAttribute('max'))
          var dataType = t.getAttribute('data-qty')

          if (dataType === 'minus' && current >= step) {
            // Minus button.
            if (current <= min || current - step < min) {
              return
            }

            input.value = current - step
          } else if (dataType === 'plus') {
            // Plus button.
            if (max && (current >= max || current + step > max)) {
              return
            }

            input.value = current + step
          }

          // Trigger event.
          input.dispatchEvent(eventChange)
          jQuery(input).trigger('input')

          // Remove disable attribute on Update Cart button on Cart page.
          var updateCart = document.querySelector("[name='update_cart']")
          if (updateCart) {
            updateCart.disabled = false
          }
        }
      }
    })
  }

  delegate(
    'click',
    e => {
      const triggerEl = e.target
      const postId = getData('quick-view-modal-id', triggerEl)
      if (postId) {
        currentPostId = postId
        pipe(
          openModal,
          activateLoader,
          clearExistingHtml,
          fetchData
        )(document.body)
      }
    },
    '[data-quick-view-modal-id]',
    body
  )

  window.addEventListener(
    'keydown',
    function (event) {
      if (event.code === 'Escape') closeModal()
    },
    true
  )

  el.addEventListener('click', function (e) {
    if (this !== e.target) {
      return
    }
    closeModal()
  })

  if (closeEl) {
    on(
      'click',
      () => {
        closeModal()
      },
      closeEl
    )
  }
}
