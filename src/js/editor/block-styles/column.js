/* global wp */
import { __ } from '@wordpress/i18n'

export default () => {
	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-primary',
		label: __('Primary Border', 'ct-bones')
	})

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-secondary',
		label: __('Secondary Border', 'ct-bones')
	})

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-dark',
		label: __('Dark Border', 'ct-bones')
	})

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-light',
		label: __('Light Border', 'ct-bones')
	})

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-gray',
		label: __('Gray Border', 'ct-bones')
	})

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-white',
		label: __('White Border', 'ct-bones')
	})

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-theme',
		label: __('Custom Theme Border', 'ct-bones')
	})
}
