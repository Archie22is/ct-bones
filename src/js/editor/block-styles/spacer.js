/* global wp */
import { __ } from '@wordpress/i18n'

export default () => {
	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'default',
		label: __('Default Spacer', 'ct-bones'),
		isDefault: true
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'mobile-only',
		label: __('Mobile Only', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'from-tablet',
		label: __('From Tablet', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'max-tablet',
		label: __('Max Tablet', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'tablet-only',
		label: __('Tablet Only', 'ct-bones'),
		isDefault: false
	})

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'desktop-only',
		label: __('Desktop Only', 'ct-bones'),
		isDefault: false
	})
}
