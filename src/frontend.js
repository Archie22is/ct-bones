/* global codetotConfig */
import { select, selectAll } from 'lib/dom'
import { initStyle } from 'lib/scripts'
import './postcss/frontend.css'

const blocks = document.querySelectorAll('[data-block]')

const initBlocks = () => {
	if (blocks) {
		blocks.forEach(block => {
			const blockName = block.getAttribute('data-block')
			if (!blockName) {
				return
			}

			require(`./js/blocks/${blockName}.js`).default(block)
		})
	}
}

const checkjQueryUIStyle = () => {
	const datePickerEl = select('.ui-date-picker')
	const datePickerTrigger = selectAll('.datepicker')

	if (datePickerEl && datePickerTrigger) {
		setTimeout(() => {
			initStyle(
				`${codetotConfig.themePath}/dynamic-assets/plugins/jquery-ui.min.css`
			)
		}, 10000)
	}
}

document.addEventListener('DOMContentLoaded', () => {
	initBlocks()
	checkjQueryUIStyle()
})
