import { select, on, toggleClass } from 'lib/dom'

const ACTIVE_CLASS = 'page-block--mobile-visible'

export default el => {
	const mobileTriggerEl = select('.js-mobile-trigger', el)

	if (mobileTriggerEl) {
		on(
			'click',
			() => {
				toggleClass(ACTIVE_CLASS, el)
			},
			mobileTriggerEl
		)
	}
}
