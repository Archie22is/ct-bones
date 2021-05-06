import { select, getData, addClass, closest } from 'lib/dom'

const getTimeRemaining = endtime => {
  const total = Date.parse(endtime) - Date.parse(new Date())
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

const renderHtml = (price, label) => {
  return `
    <span class="single-product__price__top">${price}</span>
    <span class="single-product__price__bottom">
      <span class="single-product__price__label">${label}</span>
      <span class="single-product__price__countdown js-countdown">
        <span class="single-product__price__item single-product__price__days js-days"></span>
        <span class="single-product__price__item single-product__price__hours js-hours"></span>
        <span class="single-product__price__item single-product__price__minutes js-minutes"></span>
        <span class="single-product__price__item single-product__price__seconds js-seconds"></span>
      </span>
      <span class="single-product__price__notice js-notice"></span>
    </span>
  `
}

export default el => {
  const parentEl = closest('.entry-summary', el)
  const endDate = getData('end-date', el)
  const labels = getData('labels', el) ? JSON.parse(getData('labels', el)) : []
  let noticeEl = null
  let daysEl = null
  let hoursEl = null
  let minutesEl = null
  let secondsEl = null
  let rendered = false

  const getLabels = (number, type) => {
    const matchingLabel =
      parseInt(number) > 1 ? labels[type].plural : labels[type].singular

    return `<span class="single-product__price__bottom__inner"><span class="number">${number}</span> <span class="unit">${matchingLabel}</span></span>`
  }

  const getMessage = messageType => labels['message'][messageType]
  const displayMessage = messageType => {
    if (noticeEl && getMessage(messageType)) {
      noticeEl.innerHTML = getMessage(messageType)
      addClass('display-message', el)
    }
  }

  const render = () => {
    const label = labels.message.ongoing
    const existingPriceHtml = el.innerHTML

    el.innerHTML = renderHtml(existingPriceHtml, label)

    noticeEl = select('.js-notice', el)
    daysEl = select('.js-days', el)
    hoursEl = select('.js-hours', el)
    minutesEl = select('.js-minutes', el)
    secondsEl = select('.js-seconds', el)

    addClass('is-loaded', el)

    rendered = true
  }

  if (parentEl) {
    const updateRemainingTime = () => {
      if (!rendered) {
        render()
      }

      const { total, days, hours, minutes, seconds } = getTimeRemaining(endDate)

      if (daysEl && days) {
        daysEl.innerHTML = getLabels(days, 'days')

        if (days < 1) {
          displayMessage('less_day')
        }
      }

      if (hoursEl && hours) {
        hoursEl.innerHTML = getLabels(hours, 'hours')

        if (hours < 1) {
          displayMessage('less_hour')
        }
      }

      if (minutesEl && minutes) {
        minutesEl.innerHTML = getLabels(minutes, 'minutes')
      }

      if (secondsEl && seconds) {
        secondsEl.innerHTML = getLabels(('0' + seconds).slice(-2), 'seconds')
      }

      if (total <= 0) {
        displayMessage('expired')
        addClass('is-hide', el)
        clearInterval(timeinterval)
      }
    }

    // eslint-disable-next-line no-unused-vars
    const timeinterval = setInterval(updateRemainingTime, 1000)
  }
}
