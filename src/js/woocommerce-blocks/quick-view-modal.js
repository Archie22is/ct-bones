/* eslint-disable camelcase */
/* eslint-disable space-in-parens */
/* global jQuery, codetotConfig, wc_add_to_cart_variation_params */
import { select, on, getData, addClass, removeClass, delegate, hasClass, setAttribute } from 'lib/dom'
import { pipe } from 'lib/utils'
import 'whatwg-fetch'
const $ = jQuery

const BODY_CLASS = 'is-quick-view-modal-visible'
const LOADING_CLASS = 'is-loading'
const body = document.body

const parseJSON = response => {
  return response.json()
}

const openModal = () => addClass(BODY_CLASS, body)
const closeModal = () => removeClass(BODY_CLASS, body)

export default el => {
  const closeEl = select('.js-close-modal', el)
  const sliderWrapperEl = select('.js-slider-wrapper', el)
  const contentWrapperEl = select('.js-content', el)
  const activateLoader = () => addClass(LOADING_CLASS, el)
  const deactivateLoader = () => removeClass(LOADING_CLASS, el)

  let currentPostId = null

  const clearExistingHtml = () => {
    sliderWrapperEl.innerHTML = ''
    contentWrapperEl.innerHTML = ''

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

        $(document.body).trigger('wc_fragment_refresh')

        pipe(
          initChangeVariation,
          initSlider,
          initAddToCart,
          deactivateLoader
        )(el)
      })
      .catch(function (error) {
        console.log('request failed', error)
      })

    return el
  }

  const initSlider = () => {
    const $productGalleryEl = $(el).find('.woocommerce-product-gallery')
    let wc_single_product_params = {}

    wc_single_product_params.flexslider_enabled = true
    wc_single_product_params.photoswipe_enabled = false
    wc_single_product_params.zoom_enabled = true
    wc_single_product_params.flexslider = {
      allowOneSlide: false,
      animation: 'slide',
      animationLoop: false,
      animationSpeed: 500,
      controlNav: 'thumbnails',
      directionNav: false,
      rtl: false,
      slideshow: false,
      smoothHeight: true
    }

    $productGalleryEl.trigger( 'wc-product-gallery-before-init', [ this, wc_single_product_params ] )
    $productGalleryEl.wc_product_gallery( wc_single_product_params )
    $productGalleryEl.trigger( 'wc-product-gallery-after-init', [ this, wc_single_product_params ] )

    return el
  }

  const initAddToCart = () => {
    let form = select('form.cart', el)
    if (!form || hasClass('grouped_form', form)) return

    on(
      'submit',
      e => {
        e.preventDefault()

        const productQtyInput = select('input[name=quantity]', form)
        const productIdInput = select('input[name=product_id]', form)
        const qty = productQtyInput ? Number(productQtyInput.value || 0) : 1
        let productId = productIdInput ? productIdInput.value : select('button[name="add-to-cart"]', form).value
        productId = Number(productId || 0)

        // Variation Product Type
        let variationIdInput = select('input[name=variation_id]', form)
        const variationId = variationIdInput ? Number(variationIdInput.value || 0) : null

        const button = select('button[type="submit"]', form)
        const resetVariationsEl = select('.reset_variations', form)

        let dataFetch = {
          action: 'shop_quick_view_add_to_cart',
          product_id: productId,
          product_sku: '',
          quantity: qty,
          variation_id: variationId
        }

        setAttribute('disabled', 'disabled', button)

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
          .then(function (data) {
            $(document.body).trigger('added_to_cart', [
              data.fragments,
              data.cart_hash
            ])
          })
          .then(
            () => {
              button.removeAttribute('disabled')

              $(resetVariationsEl).trigger('click')

              pipe(
                closeModal,
                deactivateLoader
              )(el)
            }
          )
          .catch(function (error) {
            console.log('request failed', error)
          })
      },
      form
    )

    return el
  }

  const initChangeVariation = () => {
    const variationFormEl = select('.variations_form', el)
    if (!variationFormEl) {
      return
    }

    // eslint-disable-next-line camelcase,valid-typeof
    if (typeof wc_add_to_cart_variation_params === 'undefined') {
      return el
    }

    $(variationFormEl).wc_variation_form()
    $(variationFormEl).find('.variations select').change()

    return el
  }

  delegate(
    'click',
    e => {
      const triggerEl = e.target
      const postId = getData('quick-view-modal-id', triggerEl)
      if (postId) {
        currentPostId = postId

        openModal()

        pipe(
          activateLoader,
          clearExistingHtml,
          fetchData
        )(el)
      }
    },
    '[data-quick-view-modal-id]',
    body
  )

  on(
    'keydown',
    e => {
      if (e.code === 'Escape') {
        closeModal()
      }
    },
    window
  )

  on('orientationchange', closeModal, window)

  on('click', e => {
    if (this !== e.target) {
      return
    }

    closeModal()
  }, el)

  if (closeEl) {
    on(
      'click',
      closeModal,
      closeEl
    )
  }
}
