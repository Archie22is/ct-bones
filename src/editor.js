/* global wp */
import domReady from '@wordpress/dom-ready'
import './postcss/editor/_index.css'
import registerSpacerStyles from './js/editor/block-styles/spacer'
import registerButtonStyles from './js/editor/block-styles/button'
import registerColumnStyles from './js/editor/block-styles/column'

domReady(() => {
	registerSpacerStyles()
	registerButtonStyles()
	registerColumnStyles()
})
