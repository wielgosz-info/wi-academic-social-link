// We're importing original, unmodified SVGs, since that what brand guidelines usually require.
import ORCIDiDIconDefault from './ORCIDiD_iconvector.svg';
import ORCIDiDIconBlack from './ORCIDiD_iconbwvector.svg';
import ORCIDiDIconWhite from './ORCID-iD_icon_reversed_vector.svg';
import arXivProfileIconDefault from './arxiv-logomark-small.svg';
import arXivProfileIconBlack from './arxiv-logomark-small-black.svg';
import arXivProfileIconWhite from './arxiv-logomark-small-white.svg';

const ORCIDiDIcon = {
	original: ORCIDiDIconDefault,
	black: ORCIDiDIconBlack,
	white: ORCIDiDIconWhite,
	'var(--wp--preset--color--black)': ORCIDiDIconBlack,
	'var(--wp--preset--color--white)': ORCIDiDIconWhite,
};

const arXivProfileIcon = {
	original: arXivProfileIconDefault,
	black: arXivProfileIconBlack,
	white: arXivProfileIconWhite,
	'var(--wp--preset--color--black)': arXivProfileIconBlack,
	'var(--wp--preset--color--white)': arXivProfileIconWhite,
};

export const AcademicIcon = ({ service, color, colorValue }) => {
	const icon = {
		'orcid-id': ORCIDiDIcon,
		'arxiv-profile': arXivProfileIcon,
	}[service];

	if (!icon) {
		return null;
	}

	const src = icon[color] || icon[colorValue] || icon.original;

	return (
		<div className="wp-block-academic-social-link__icon">
			<img
				className="wp-block-academic-social-link__icon-img"
				src={src}
				alt=""
			/>
		</div>
	);
};
