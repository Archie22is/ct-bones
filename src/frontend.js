import {
	getHeight,
	getTopOffset,
	on,
	select,
	selectAll,
	scrollTo
} from 'lib/dom'
import './postcss/global/_index.css'
import './postcss/frontend/_index.css'
import SlideOutMenu from './theme-components/SlideoutMenu'
import ModalSearchForm from './theme-components/ModalSearchForm'
import { render, Fragment } from '@wordpress/element'

const App = () => {
	const slideOutMenuEl = select('[data-theme-component="slideout-menu"]') ?? null
	const modalSearchFormEl = select('[data-theme-component="modal-search-form"]') ?? null

	return (
		<Fragment>
			<SlideOutMenu el={slideOutMenuEl} />
			<ModalSearchForm el={modalSearchFormEl} />
		</Fragment>
	)
}

const initAnchorLinks = () => {
	const linkEls = selectAll('a[href^="#"')
	let scrolling = false

	if (linkEls && linkEls.length) {
		on(
			'click',
			e => {
				if (scrolling) {
					return
				}

				const target = e.target.href
				const targetParts = target.split('#')
				const targetContext = targetParts[targetParts.length - 1]
				const targetEl = targetContext ? select(`#${targetContext}`) : null
				const position = targetEl ? getTopOffset(targetEl) : null
				const offsetEl = select('.header')
				const offset = offsetEl ? getHeight(offsetEl) : 0

				if (targetEl && position) {
					scrolling = true
					scrollTo(position - offset)

					scrolling = false
				}
			},
			linkEls
		)
	}
}

document.addEventListener('DOMContentLoaded', () => {
	initAnchorLinks()

	render(<App />, select('#ct-bones-app'))
})
