import {
	on,
	selectAll,
	addClass,
	removeClass,
	toggleClass,
	closest,
	trigger
} from 'lib/dom'

const body = document.body
const SLIDEOUT_VISIBLE_CLASS = 'is-slideout-visible'

const SUB_MENU_VISIBLE_CLASS = 'is-active'

export default el => {
	const closeEls = selectAll('.js-mobile-menu-close', el)
	const triggers = selectAll('.js-toggle-sub-menu', el)

	on(
		'slideout.visible',
		() => {
			addClass(SLIDEOUT_VISIBLE_CLASS, body)
		},
		body
	)

	if (triggers) {
		on(
			'click',
			e => {
				const parentTrigger = closest('.menu-item-has-children', e.target)
				toggleClass(SUB_MENU_VISIBLE_CLASS, e.target)
				toggleClass(SUB_MENU_VISIBLE_CLASS, parentTrigger)
			},
			triggers
		)
	}

	on(
		'slideout.hidden',
		() => {
			removeClass(SLIDEOUT_VISIBLE_CLASS, body)
		},
		body
	)

	if (closeEls) {
		on(
			'click',
			() => {
				trigger('slideout.hidden', body)
			},
			closeEls
		)
	}
}
