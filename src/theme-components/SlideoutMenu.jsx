import classnames from 'classnames'
import { useEffect, useState } from '@wordpress/element'
import { select, trigger, on, remove } from 'lib/dom'

const SlideOutMenu = props => {
	const [active, setActive] = useState(false)
	const [closeButton, setCloseButton] = useState('')
	const [searchForm, setSearchForm] = useState('')
	const [menu, setMenu] = useState('')
	const [wpmlFlags, setWpmlFlags] = useState('')
	const [loaded, setLoaded] = useState(false)

	const classNames = classnames('slideout-menu', active ? 'is-active' : '')

	useEffect(() => {
		const closeButtonEl = select('#slideout-menu-close-button', props.el)
		const formEl = select('#slideout-menu-search-form', props.el)
		const menuEl = select('#slideout-menu-menu', props.el)
		const wpmlFlagsEl = select('#slideout-menu-wpml-language-flags', props.el)

		if (closeButtonEl) {
			setCloseButton(closeButtonEl.innerHTML)
		}

		if (formEl) {
			setSearchForm(formEl.innerHTML)
		}

		if (menuEl) {
			setMenu(menuEl.innerHTML)
		}

		if (wpmlFlagsEl) {
			setWpmlFlags(wpmlFlagsEl.innerHTML)
		}

		on(
			'slideout.visible',
			() => {
				setActive(true)
			},
			document.body
		)

		on(
			'slideout.hidden',
			() => {
				setActive(false)
			},
			document.body
		)

		setLoaded(true)

		if (loaded) {
			remove(props.el)
		}
	}, [props.el, loaded])

	const handleClose = () => {
		trigger('slideout.hidden', document.body)
	}

	return (
		<>
			{loaded ? (
				<div className={classNames}>
					{closeButton ? (
						<div className={'slideout-menu__overlay'}>
							<button
								className={'slideout-menu__close-button'}
								dangerouslySetInnerHTML={{ __html: closeButton }}
								onClick={handleClose}
							></button>
						</div>
					) : (
						''
					)}
					<div className={'slideout-menu__wrapper'}>
						<div className={'slideout-menu__inner'}>
							{searchForm ? (
								<div
									className={'slideout-menu__block is-search-form'}
									dangerouslySetInnerHTML={{ __html: searchForm }}
								></div>
							) : (
								''
							)}
							{menu ? (
								<div
									className={'slideout-menu__block is-menu'}
									dangerouslySetInnerHTML={{ __html: menu }}
								></div>
							) : (
								''
							)}
							{wpmlFlags ? (
								<div
									className={'slideout-menu__block is-wpml-flags'}
									dangerouslySetInnerHTML={{ __html: wpmlFlags }}
								></div>
							) : (
								''
							)}
						</div>
					</div>
				</div>
			) : (
				''
			)}
		</>
	)
}

export default SlideOutMenu
