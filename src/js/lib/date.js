const parseDate = dateString => {
	return Date.parse(dateString)
}

const getRemainingTime = time => {
	const currentTime = new Date()
	const timeObj = new Date(time)

	const total =
		currentTime - timeObj > 0 ? currentTime - timeObj : timeObj - currentTime

	if (total > 0) {
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
	} else {
		console.log('error: ' + total)

		return null
	}
}

export { parseDate, getRemainingTime }
