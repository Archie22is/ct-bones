import { select, on, getData, addClass, removeClass, hasClass } from 'lib/dom'

const VISIBLE_CLASS = 'is-content-visible'
const NO_INIT_CLASS = 'is-not-init'

export default el => {
	const contentEl = select('.js-content', el)
	const maxHeight = getData('max-height', el)
	const openEl = select('.js-open-trigger .wp-block-button__link', el)
	const defaultButtonText = openEl.innerText
	const expandedText = openEl ? getData('expanded-text', openEl) : null

	const open = () => {
		addClass(VISIBLE_CLASS, el)
		contentEl.setAttribute('style', `height: auto`)

		if (expandedText) {
			openEl.innerText = expandedText
		}
	}

	const close = () => {
		contentEl.setAttribute('style', `height: ${maxHeight}px`)
		removeClass(VISIBLE_CLASS, el)

		window.scrollTo(0, 0)

		openEl.innerText = defaultButtonText
	}

	const noInit = () => {
		removeClass(VISIBLE_CLASS, el)
		addClass(NO_INIT_CLASS, el)
	}

	const init = () => {
		const contentHeight = contentEl.scrollHeight || contentEl.offsetHeight

		if (contentHeight <= maxHeight) {
			noInit()
		} else {
			close()
		}
	}

	init()

	if (openEl) {
		on(
			'click',
			() => {
				if ( hasClass(VISIBLE_CLASS, el) ) {
					close()
				} else {
					open()
				}
			},
			openEl
		)
	}
}
