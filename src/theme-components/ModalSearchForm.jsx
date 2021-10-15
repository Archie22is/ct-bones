import { useEffect, useState } from '@wordpress/element'
import { select, remove } from 'lib/dom'
import Modal from './Modal'

const ModalSearchForm = props => {
	const [closeButton, setCloseButton] = useState('')
	const [title, setTitle] = useState('')
	const [content, setContent] = useState('')
	const [loaded, setLoaded] = useState(false)

	useEffect(() => {
		if (!loaded && props.el) {
			const closeButtonEl = select('#modal-search-form-close-button', props.el)
			const titleEl = select('#modal-search-form-title', props.el)
			const contentEl= select('#modal-search-form', props.el)

			if (closeButtonEl) {
				setCloseButton(closeButtonEl.innerHTML)
			}

			if (titleEl) {
				setTitle(titleEl.innerHTML)
			}

			if (contentEl) {
				setContent(contentEl.innerHTML)
			}

			remove(props.el)

			setLoaded(true)
		}
	}, [props.el, loaded])

	return (
		<Modal
		className={'modal--search-form'}
		id={'modal-search-form'}
		closeButton={closeButton}
		header={title}
		content={content}
		hasOverlay={true}
		/>
	)
}

export default ModalSearchForm
