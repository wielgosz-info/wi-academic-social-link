import { registerBlockType } from '@wordpress/blocks';
import { store as blocksStore } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';

import './style.scss';

import variations from './variations';
import metadata from './block.json';

console.log('registerBlockType', metadata);

registerBlockType( metadata.name, {
	edit: (props) => {
		const socialLinkBlock = useSelect( ( select ) => {
			return select( blocksStore ).getBlockType('core/social-link');
		}, [] );

		return socialLinkBlock.edit(props);
	},
	variations,
} );
