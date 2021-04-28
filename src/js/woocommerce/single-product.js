/* global jQuery */
const $ = jQuery

const singleProduct = () => {
  $(document).ready(function () {
    $(window).on('scroll', function () {
      if ($('html, body').scrollTop() > 200) {
        $('.single-product__floating_product_bar').addClass('is-active')
      } else {
        $('.single-product__floating_product_bar').removeClass('is-active')
      }
    })
  })
}

export { singleProduct }
