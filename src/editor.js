/* global wp */
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import './postcss/editor/_index.css';

domReady(() => {
	// Core spacing
	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'default',
		label: __('Default Spacer', 'ct-bones'),
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'small',
		label: __('Small', 'ct-bones'),
		isDefault: false,
	});

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'medium',
		label: __('Medium', 'ct-bones'),
		isDefault: false,
	});

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'large',
		label: __('Large', 'ct-bones'),
		isDefault: false,
	});

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'mobile-only',
		label: __('Mobile Only', 'ct-bones'),
		isDefault: false,
	});

	wp.blocks.registerBlockStyle('core/spacer', {
		name: 'desktop-only',
		label: __('Desktop Only', 'ct-bones'),
		isDefault: false,
	});

	// Button style
	wp.blocks.registerBlockStyle('core/button', {
		name: 'link',
		label: __('Link', 'ct-bones')
	});

	// Border style for core/column block
	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-primary',
		label: __('Primary Border', 'ct-bones')
	});

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-secondary',
		label: __('Secondary Border', 'ct-bones')
	});

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-dark',
		label: __('Dark Border', 'ct-bones')
	});

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-light',
		label: __('Light Border', 'ct-bones')
	});

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-gray',
		label: __('Gray Border', 'ct-bones')
	});

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-white',
		label: __('White Border', 'ct-bones')
	});

	wp.blocks.registerBlockStyle('core/column', {
		name: 'border-theme',
		label: __('Custom Theme Border', 'ct-bones')
	});
})
