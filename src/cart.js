/* eslint-disable camelcase */
/* global jQuery */
import { throttle } from 'lib/utils'

jQuery(function ($) {
  const triggerUpdateButton = e => {
    $("[name='update_cart']").removeAttr('disabled')
    $("[name='update_cart']").trigger('click')
  }

  $(document.body).on('change', 'input.qty', throttle(triggerUpdateButton, 300))
})
