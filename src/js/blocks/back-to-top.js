import { on, select, addClass, removeClass } from 'lib/dom'
import { throttle } from 'lib/utils'

const headerEl = select('[data-block="header"]')
const ACTIVE_EL_CLASS = 'back-to-top--visible'

const scrollTo = (scrollTargetY, speed = 2000, easing = 'easeOutSine') => {
	const scrollY = window.scrollY || document.documentElement.scrollTop
	const _scrollTargetY = scrollTargetY || 0
	const _speed = speed || 2000
	const _easing = easing || 'easeOutSine'
	let currentTime = 0

	// min time .1, max time .8 seconds
	const time = Math.max(
		0.1,
		Math.min(Math.abs(scrollY - scrollTargetY) / _speed, 0.8)
	)

	// easing equations from https://github.com/danro/easing-js/blob/master/easing.js
	const easingEquations = {
		easeOutSine: function (pos) {
			return Math.sin(pos * (Math.PI / 2))
		},
		easeInOutSine: function (pos) {
			return -0.5 * (Math.cos(Math.PI * pos) - 1)
		},
		easeInOutQuint: function (pos) {
			if ((pos /= 0.5) < 1) {
				return 0.5 * Math.pow(pos, 5)
			}
			return 0.5 * (Math.pow(pos - 2, 5) + 2)
		}
	}

	// add animation loop
	const tick = () => {
		currentTime += 1 / 60

		const p = currentTime / time
		const t = easingEquations[_easing](p)

		if (p < 1) {
			window.requestAnimationFrame(tick)

			window.scrollTo(0, scrollY + (_scrollTargetY - scrollY) * t)
		} else {
			window.scrollTo(0, _scrollTargetY)
		}
	}

	// call it once to get started
	tick()
}

export default el => {
	on(
		'click',
		function () {
			scrollTo(0)
		},
		el
	)

	on(
		'scroll',
		throttle(function () {
			const scrollTop = window.pageYOffset || document.body.scrollTop
			const offset = headerEl ? headerEl.offsetHeight + 20 : 0
			if (scrollTop > offset) {
				addClass(ACTIVE_EL_CLASS, el)
			} else {
				removeClass(ACTIVE_EL_CLASS, el)
			}
		}, 100),
		window
	)
}
