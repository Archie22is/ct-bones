/* global wp */
import { __ } from '@wordpress/i18n';

export default () => {
	wp.blocks.registerBlockStyle('core/button', {
		name: 'link',
		label: __('Link', 'ct-bones')
	});
}
