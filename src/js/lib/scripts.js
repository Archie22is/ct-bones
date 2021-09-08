const initScript = src => {
	const script = document.createElement('script')
	script.src = src
	script.type = 'text/javascript'
	document.getElementsByTagName('head')[0].appendChild(script)
}

const initStyle = src => {
	const script = document.createElement('link')
	script.rel = 'stylesheet'
	script.href = src
	document.head.appendChild(script)
}

const initMapScript = apiKey => {
	const url = `https://maps.googleapis.com/maps/api/js?key=${apiKey}`

	initScript(url)
}

export { initScript, initStyle, initMapScript }
