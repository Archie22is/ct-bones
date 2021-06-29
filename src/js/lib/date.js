const parseDate = dateString => {
  const formattedDate =
    dateString.replace(/[a-z]+/gi, ' ').replace(/ /g, 'T') + 'Z'
  const parsed = Date.parse(formattedDate)

  return parsed
}

const getRemainingTime = (time, type = 'end') => {
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

export { parseDate, getRemainingTime }
