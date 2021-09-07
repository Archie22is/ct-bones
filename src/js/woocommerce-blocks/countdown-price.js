/* global CODETOT_COUNTDOWN_LABELS */
import { select, getData, addClass, removeClass } from 'lib/dom'
import { parseDate, getRemainingTime } from 'lib/date'
import { renderHtml } from 'lib/countdown'

export default el => {
	const startDate = getData('start-date', el)
		? parseDate(getData('start-date', el))
		: null
	const endDate = getData('end-date', el)
		? parseDate(getData('end-date', el))
		: null
	const labels = CODETOT_COUNTDOWN_LABELS
		? JSON.parse(CODETOT_COUNTDOWN_LABELS)
		: []
	let noticeEl = null
	let daysEl = null
	let hoursEl = null
	let minutesEl = null
	let secondsEl = null
	let labelEl = null
	let rendered = false

	const getLabels = (number, type) => {
		const matchingLabel =
			parseInt(number) > 0 ? labels[type].plural : labels[type].singular

		const displayNumber = number > 1 ? number : 0

		return `<span class="single-product__price__bottom__inner"><span class="number">${displayNumber}</span> <span class="unit">${matchingLabel}</span></span>`
	}

	const getMessage = messageType => labels['message'][messageType]
	const displayMessage = messageType => {
		removeClass('display-message', el)

		if (noticeEl && getMessage(messageType)) {
			noticeEl.innerHTML = getMessage(messageType)
			addClass('display-message', el)
		}

		return el
	}

	const render = () => {
		const existingPriceHtml = el.innerHTML
		el.innerHTML = renderHtml(existingPriceHtml)

		noticeEl = select('.js-notice', el)
		daysEl = select('.js-days', el)
		hoursEl = select('.js-hours', el)
		minutesEl = select('.js-minutes', el)
		secondsEl = select('.js-seconds', el)
		labelEl = select('.js-label', el)

		addClass('is-loaded', el)

		rendered = true
	}

	const updateDay = days => {
		if (daysEl) {
			daysEl.innerHTML = getLabels(days, 'days')

			if (days <= 1) {
				displayMessage('less_day')
			}
		}
	}

	const updateHour = hours => {
		if (hoursEl) {
			hoursEl.innerHTML = getLabels(hours, 'hours')

			if (hours < 1) {
				displayMessage('less_hour')
			}
		}
	}

	const updateMinute = minutes => {
		if (minutesEl && minutes) {
			minutesEl.innerHTML = getLabels(minutes, 'minutes')
		}
	}

	const updateSecond = seconds => {
		if (secondsEl && seconds) {
			secondsEl.innerHTML = getLabels(('0' + seconds).slice(-2), 'seconds')
		}
	}

	const updateOnGoingTime = () => {
		if (getRemainingTime(endDate)) {
			const { days, hours, minutes, seconds } = getRemainingTime(endDate)

			updateDay(days)
			updateHour(hours)
			updateMinute(minutes)
			updateSecond(seconds)
		}
	}

	const updateScheduledTime = () => {
		if (getRemainingTime(startDate)) {
			const { days, hours, minutes, seconds } = getRemainingTime(startDate)

			updateDay(days)
			updateHour(hours)
			updateMinute(minutes)
			updateSecond(seconds)
		}
	}

	const updateRemainingTime = () => {
		if (!rendered) {
			render()
		}

		const currentTime = Date.parse(new Date())

		if (startDate && startDate > currentTime) {
			updateScheduledTime()
			labelEl.innerHTML = getMessage('not_start')

			if (getRemainingTime(startDate)) {
				const { total } = getRemainingTime(startDate)

				if (total <= 0) {
					reset()
				}
			} else {
				console.error(['error with endDate', startDate])
			}
		} else if (endDate && endDate >= currentTime) {
			labelEl.innerHTML = getMessage('ongoing')

			updateOnGoingTime()

			if (getRemainingTime(endDate)) {
				const { total } = getRemainingTime(endDate)

				if (total <= 0) {
					reset()
				}
			} else {
				console.error(['error with endDate', endDate])
			}
		} else {
			console.error(['no matching startDate or endDate', startDate, endDate])
		}
	}

	// eslint-disable-next-line no-unused-vars
	const timeinterval = setInterval(updateRemainingTime, 1000)

	const reset = () => {
		clearInterval(timeinterval)

		setTimeout(() => {
			window.location.reload()
		}, 1000)

		return el
	}

	return el
}
