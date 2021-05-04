import { select, getData } from 'lib/dom'

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

export default el => {
  const endDate = getData('end-date', el)
  const labels = getData('labels', el) ? JSON.parse(getData('labels', el)) : []

  const getLabels = (number, type) => {
    const matchingLabel = parseInt(number) > 1 ? labels[type].plural : labels[type].singular

    return `<span class="number">${number}</span> <span class="unit">${matchingLabel}</span>`
  }

  const daysEl = select('.js-days', el)
  const hoursEl = select('.js-hours', el)
  const minutesEl = select('.js-minutes', el)
  const secondsEl = select('.js-seconds', el)

  const updateRemainingTime = () => {
    const { total, days, hours, minutes, seconds } = getTimeRemaining(endDate)

    if (daysEl && days) {
      daysEl.innerHTML = getLabels(days, 'days')
    }

    if (hoursEl && hours) {
      hoursEl.innerHTML = getLabels(hours, 'hours')
    }

    if (minutesEl && minutes) {
      minutesEl.innerHTML = getLabels(minutes, 'minutes')
    }

    if (secondsEl && seconds) {
      secondsEl.innerHTML = getLabels(('0' + seconds).slice(-2), 'seconds')
    }

    if (total <= 0) {
      clearInterval(timeinterval)
    }
  }

  const timeinterval = setInterval(updateRemainingTime, 1000)
}
