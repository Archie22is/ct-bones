/* global jQuery */

// Create Minus button.
const minusBtn = () => {
  var minusBtn = document.createElement('span')

  minusBtn.setAttribute('class', 'product-qty')
  minusBtn.setAttribute('data-qty', 'minus')
  minusBtn.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path d="M15 8v1H2V8h13z"/></svg>'

  return minusBtn
}

// Create Plus button.
const plusBtn = () => {
  var plusBtn = document.createElement('span')

  plusBtn.setAttribute('class', 'product-qty')
  plusBtn.setAttribute('data-qty', 'plus')
  plusBtn.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path d="M16 9H9v7H8V9H1V8h7V1h1v7h7v1z"/></svg>'

  return plusBtn
}

// Add Minus and Plus button on Product Quantity.
const customQuantity = () => {
  var quantity = document.querySelectorAll('.quantity')

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
    var inStock = productInfo ? productInfo.getAttribute('data-in_stock') : 'no'
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

export { customQuantity, minusBtn, plusBtn }
