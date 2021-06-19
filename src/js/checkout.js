/* eslint-disable camelcase */
/* global jQuery */
import { select, delegate } from 'lib/dom'

jQuery(function ($) {
  const updatePromotionForm = () => {
    const formEl = select('form[name="checkout"]')
    const couponForm = select('form.checkout_coupon')
    const couponFormInput = couponForm
      ? select('input[name="coupon_code"]', couponForm)
      : null
    const customCouponInput = formEl
      ? select('input[name="custom_coupon_code"]', formEl)
      : null

    const promotionValue = customCouponInput ? customCouponInput.value : null

    if (promotionValue && couponForm && couponFormInput) {
      couponFormInput.value = promotionValue

      $(couponForm).trigger('submit')
    }
  }

  delegate('click', updatePromotionForm, '.js-coupon-trigger', document.body)
})
