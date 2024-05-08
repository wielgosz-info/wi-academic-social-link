import { AcademicIcon } from './icons';

const variations = [
	{
		isDefault: true,
		name: 'orcid-id',
		attributes: { service: 'orcid-id' },
		title: 'ORCID iD',
		icon: <AcademicIcon service="orcid-id" color="original" />,
	},
	{
		name: 'arxiv-profile',
		attributes: { service: 'arxiv-profile' },
		title: 'arXiv Profile',
		icon: <AcademicIcon service="arxiv-profile" color="original" />,
	},
];

/**
 * Add `isActive` function to all `social link` variations, if not defined.
 * `isActive` function is used to find a variation match from a created
 *  Block by providing its attributes.
 */
variations.forEach((variation) => {
	if (variation.isActive) return;
	variation.isActive = (blockAttributes, variationAttributes) =>
		blockAttributes.service === variationAttributes.service;
});

export default variations;
