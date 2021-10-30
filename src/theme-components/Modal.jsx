import classnames from 'classnames'
import { useState, useEffect } from '@wordpress/element'
import { selectAll, on, addClass, removeClass } from 'lib/dom'

const body = document.body
const MODAL_CLASS = 'is-modal-active'

const Modal = props => {
	const [active, setActive] = useState(false)
	const classNames = classnames('modal', props.className, active ? 'modal--visible' : '')

	useEffect(() => {
		const triggerEls = selectAll('[data-modal-component-open="' + props.id + '"]')

		if (triggerEls.length) {
			on('click', () => {
				setActive(true)

				addClass(MODAL_CLASS, body)
			}, triggerEls)
		}
	}, [])

	const handleClose = () => {
		setActive(false)

		removeClass(MODAL_CLASS, body)
	}

	return (
		<div className={classNames} id={props.id}>
			{ props.hasOverlay ? <div className={'modal__overlay'} onClick={handleClose}></div> : ''}
			<div className={'modal__wrapper'}>
				{ props.closeButton ? <button className={'modal__close-button'} dangerouslySetInnerHTML={{ __html: props.closeButton }} onClick={handleClose}></button> : ''}
				{ props.header ? <div className={'modal__header'} dangerouslySetInnerHTML={{ __html: props.header }}></div> : ''}
				{ props.content ? <div className={'modal__content'} dangerouslySetInnerHTML={{ __html: props.content }}></div> : ''}
			</div>
		</div>
	)
}

export default Modal
