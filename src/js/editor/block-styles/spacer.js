/* global wp */
import { __ } from '@wordpress/i18n'

export default () => {
	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'default',
		label: __('Default Spacer', 'ct-bones'),
		isDefault: true
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'small',
		label: __('Small', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'medium',
		label: __('Medium', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'large',
		label: __('Large', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'mobile-only',
		label: __('Mobile Only', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'desktop-only',
		label: __('Desktop Only', 'ct-bones'),
		isDefault: false
	})
}
