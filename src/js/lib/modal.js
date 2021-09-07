import {
	on,
	select,
	selectAll,
	trigger,
	addClass,
	removeClass,
	loadNoscriptContent
} from 'lib/dom'
import { map } from 'lib/utils'

const mergeTwoArrays = (oldArray, newArray) => {
	return [...oldArray, ...newArray]
}

const getElementsBySelectors = (selectors, id) => {
	let outputItems = []

	if (!selectors) {
		return outputItems
	}

	selectors.forEach(selector => {
		const realSelector = selector.replace('ID', id) // Replace ID with real modal id
		const items = selectAll(realSelector)

		if (items && items.length) {
			outputItems = mergeTwoArrays(outputItems, items)
		}
	})

	return outputItems
}

const body = document.body
const BODY_MODAL_CLASS = 'is-modal-activate'

export default (el, customOptions = {}) => {
	const contentEl = select('.js-content', el)
	const defaultOptions = {
		id: 'ID',
		modalWrapper: '.modal__wrapper',
		activeClass: 'modal--visible',
		openTriggers: ['a[href="#ID"]', '[data-open-modal="ID"]'],
		closeTriggers: ['[data-close-modal="ID"]'],
		lazyload: false,
		enableClickOverlay: true
	}

	const options = { ...defaultOptions, ...customOptions }
	const activate = () => {
		if (options.lazyload) {
			loadNoscriptContent(contentEl)
		}

		addClass(BODY_MODAL_CLASS, body)
		addClass(options.activeClass, el)
	}
	const deactivate = () => {
		removeClass(BODY_MODAL_CLASS, body)
		removeClass(options.activeClass, el)
	}

	// Load open triggers
	const openTriggers = getElementsBySelectors(options.openTriggers, options.id)

	// Define close triggers
	const closeTriggers = getElementsBySelectors(
		options.closeTriggers,
		options.id
	)

	on('activate', activate, el)
	on('deactivate', deactivate, el)

	// Click modal overlay: Close
	if (options.enableClickOverlay) {
		on(
			'click',
			e => {
				if (e.target === el) {
					trigger('deactivate', el)
				}
			},
			el
		)
	}

	if (openTriggers) {
		map(openTrigger => {
			on(
				'click',
				e => {
					e.preventDefault()
					trigger('activate', el)
				},
				openTrigger
			)
		}, openTriggers)
	}

	if (closeTriggers) {
		on(
			'click',
			e => {
				e.preventDefault()

				trigger('deactivate', el)
			},
			closeTriggers
		)
	}
}
