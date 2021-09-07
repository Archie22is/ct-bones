import Flickity from 'flickity'
import {
	getModuleOptions,
	loadNoscriptContent,
	on,
	addClass,
	removeClass
} from 'lib/dom'
import { throttle } from 'lib/utils'
require('flickity-as-nav-for')

const MODULE_NAME = 'carousel'
const INIT_CLASS = 'is-initialized'

export default (el, options = {}) => {
	const defaults = {
		prevNextButtons: true,
		pageDots: true,
		cellAlign: 'left',
		percentPosition: false,
		items: 1,
		lazyload: false,
		watchCSS: false,
		on: {
			ready: () => {
				addClass('is-ready', el)
			}
		},
		arrowShape: {
			x0: 10,
			x1: 50,
			y1: 50,
			x2: 55,
			y2: 45,
			x3: 20
		}
	}
	const args = getModuleOptions(MODULE_NAME, el, defaults)
	const finalArgs = { ...args, ...options }

	const resize = () => addClass(INIT_CLASS, el)
	const reset = () => removeClass(INIT_CLASS, el)

	if (el.childElementCount > args.items) {
		const flickity = new Flickity(el, finalArgs)

		if (finalArgs.lazyload) {
			flickity.on('change', () => {
				const currentSlide = flickity.selectedElement

				loadNoscriptContent(currentSlide)
			})
		}

		const handler = () => {
			reset()
			flickity.resize()
			resize()
		}

		handler()

		on('change', handler, window)
		on('resize', throttle(handler, 300), window)

		return flickity
	}

	return null
}
