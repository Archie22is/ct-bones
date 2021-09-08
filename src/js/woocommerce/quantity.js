/* global jQuery */
import {
	select,
	selectAll,
	closest,
	getAttribute,
	setAttribute,
	addClass,
	removeClass,
	getData,
	on
} from 'lib/dom'

const $ = jQuery

// Create Minus button.
const createMinusButton = () => {
	let el = document.createElement('span')

	addClass('product-qty', el)
	setAttribute('data-qty', 'minus', el)
	el.innerHTML =
		'<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path d="M15 8v1H2V8h13z"/></svg>'

	return el
}

// Create Plus button.
const createPlusButton = () => {
	let el = document.createElement('span')

	addClass('product-qty', el)
	setAttribute('data-qty', 'plus', el)
	el.innerHTML =
		'<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path d="M16 9H9v7H8V9H1V8h7V1h1v7h7v1z"/></svg>'

	return el
}

const initQuantity = ele => {
	// Input.
	var input = select('input.qty', ele)
	if (!input) {
		return
	}

	// Add class ajax-ready on first load.
	addClass('is-loading', ele)
	addClass('ajax-ready', ele)

	// Append Minus button before Input.
	if (!select('.product-qty[data-qty="minus"]', ele)) {
		ele.insertBefore(createMinusButton(), input)
	}

	// Append Plus button after Input.
	if (!select('.product-qty[data-qty="plus"]', ele)) {
		ele.appendChild(createPlusButton())
	}

	// Vars.
	const cart = closest('form.cart', ele)
	const buttons = selectAll('.product-qty', ele)
	const maxInput = Number(getAttribute('max', input))
	// eslint-disable-next-line no-undef
	const eventChange = new Event('change')

	// Get product info.
	const productInfo = cart ? select('.additional-product', cart) : false
	const inStock = productInfo ? getData('in_stock', productInfo) : 'no'
	const outStock = productInfo
		? getData('out_of_stock', productInfo)
		: 'Out of stock'
	const notEnough = productInfo ? getData('not_enough', productInfo) : ''
	const quantityValid = productInfo
		? getData('valid_quantity', productInfo)
		: ''

	// Check valid quantity.
	on(
		'change',
		e => {
			var inputVal = e.target.value
			var inCartQty = productInfo ? Number(productInfo.value || 0) : 0
			var min = Number(getAttribute('min', input) || 0)
			const ajaxReady = () => removeClass('ajax-ready', input)

			// When quantity updated.
			addClass('ajax-ready', input)

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
		},
		input
	)

	// Minus & Plus button click.
	on(
		'click',
		e => {
			const current = Number(input.value || 0)
			const step = Number(getAttribute('step', input) || 1)
			const min = Number(getAttribute('min', input) || 0)
			const max = Number(getAttribute('max', input) || 100)
			const dataType = getAttribute('data-qty', e.target)

			if (dataType === 'minus' && current >= step) {
				// Minus button.
				if (current <= min || current - step < min) {
					return
				}

				input.value = current - step
			}

			if (dataType === 'plus') {
				// Plus button.
				if (max && (current >= max || current + step > max)) {
					return
				}

				input.value = current + step
			}

			// Trigger event.
			input.dispatchEvent(eventChange)
			$(input).trigger('change')
		},
		buttons
	)

	removeClass('is-loading', ele)
}

export { initQuantity }
