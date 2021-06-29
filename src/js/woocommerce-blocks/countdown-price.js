/* global CODETOT_COUNTDOWN_LABELS */
import { select, getData, addClass, removeClass } from 'lib/dom'

const getTimeRemaining = (time, type = 'end') => {
  const total =
    type === 'end'
      ? time - Date.parse(new Date())
      : Date.parse(new Date()) - time

  const seconds = Math.floor((total / 1000) % 60)
  const minutes = Math.floor((total / 1000 / 60) % 60)
  const hours = Math.floor((total / (1000 * 60 * 60)) % 24)
  const days = Math.floor(total / (1000 * 60 * 60 * 24))

  return {
    total,
    days,
    hours,
    minutes,
    seconds
  }
}

const renderHtml = price => {
  return `
    <span class="single-product__price__top"><span class="price">${price}</span></span>
    <span class="single-product__price__bottom">
      <span class="single-product__price__label js-label"></span>
      <span class="single-product__price__countdown js-countdown">
        <span class="single-product__price__item single-product__price__days js-days"></span>
        <span class="single-product__price__item single-product__price__hours js-hours"></span>
        <span class="single-product__price__item single-product__price__minutes js-minutes"></span>
        <span class="single-product__price__item single-product__price__seconds js-seconds"></span>
      </span>
    </span>
    <span class="single-product__price__notice js-notice"></span>
  `
}

const parseDate = dateString => {
  const parsed = Date.parse(dateString)
  if (!isNaN(parsed)) {
    return dateString
  }

  return Date.parse(dateString.replace(/-/g, '/').replace(/[a-z]+/gi, ' '))
}

export default el => {
  const startDate = getData('start-date', el) ? parseDate(getData('start-date', el)) : null
  const endDate = getData('end-date', el) ? parseDate(getData('end-date', el)) : null
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

    return `<span class="single-product__price__bottom__inner"><span class="number">${number}</span> <span class="unit">${matchingLabel}</span></span>`
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

      if (days < 1) {
        displayMessage('less_day')
        addClass('hide-day', el)
      }
    }
  }

  const updateHour = hours => {
    if (hoursEl) {
      hoursEl.innerHTML = getLabels(hours, 'hours')

      if (hours < 1) {
        displayMessage('less_hour')
        addClass('hide-hour', el)
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
    const { days, hours, minutes, seconds } = getTimeRemaining(endDate, 'end')

    updateDay(days)
    updateHour(hours)
    updateMinute(minutes)
    updateSecond(seconds)
  }

  const updateScheduledTime = () => {
    const { days, hours, minutes, seconds } = getTimeRemaining(
      startDate,
      'start'
    )

    updateDay(days)
    updateHour(hours)
    updateMinute(minutes)
    updateSecond(seconds)
  }

  const updateRemainingTime = () => {
    if (!rendered) {
      render()
    }

    const currentTime = Date.parse(new Date())

    if (startDate && startDate > currentTime) {
      updateScheduledTime()
      labelEl.innerHTML = getMessage('not_start')

      const { total } = getTimeRemaining(startDate)

      if (total <= 0) {
        reset()
      }
    } else if (endDate && endDate >= currentTime) {
      labelEl.innerHTML = getMessage('ongoing')

      updateOnGoingTime()

      const { total } = getTimeRemaining(endDate)

      if (total <= 0) {
        reset()
      }
    } else {
      console.log('DEBUG: There is unknown error with countdown settings.')
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
