/**
 * Custom button block
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks, RichText, InspectorControls } from '@wordpress/blockEditor';
import { PanelBody, RadioControl } from '@wordpress/components';
import { __experimentalLinkControl as LinkControl } from '@wordpress/blockEditor';

// Parent block
registerBlockType('paws/buttons', {
	title: 'Buttons',
	icon: 'button',
	description: 'Prompt visitors to take action with a group of button-style links.',
	category: 'text',
	apiVersion: 2,

	/**
	 * The edit function
	 */
	edit: ( props ) => {

		const blockPropsEdit = useBlockProps( { className: "block-buttons", } );
		
		return (
			<div {...blockPropsEdit}>
				<InnerBlocks
					template={[['paws/button']]}
					templateLock={false}
					allowedBlocks={['paws/button']}
					direction="horizontal"
					renderAppender={ InnerBlocks.ButtonBlockAppender }
				/>
			</div>
		);
	},
	
	/**
	 * The save function
	 */
	save: ( props ) => {

		const blockPropsSave = useBlockProps.save({
			className: "block-buttons",
		});

		return (
			<div {...blockPropsSave}>
				<InnerBlocks.Content />
			</div>
		);
	}
});

// Child block
registerBlockType('paws/button', {
	title: 'Button',
	icon: 'button',
	category: 'text',
	apiVersion: 2,
	parent: ['paws/buttons'],
	supports: {
		color: {
			background: true,
			text: false
		}
	},
	attributes: {
		buttonText: {
			type: 'string',
			default: ''
		},
		prominence: {
			type: "string",
			default: "primary",
		},
		linkValue: {
			type: 'object',
			default: {}
		}
	},

	/**
	 * The edit function
	 */
	edit: ({ setAttributes, attributes: at }) => {

		const blockPropsEdit = useBlockProps({
			className: "block-button " + at.prominence,
		});

		return (
			<>
				<div {...blockPropsEdit}>
					<RichText
						tagName="span"
						className="block-button__text"
						value={at.buttonText}
						allowedFormats={ [ ] }
						onChange={(value) => setAttributes({ buttonText: value })}
						placeholder="Button text"
					/>
				</div>

				<InspectorControls>

					<PanelBody title={"Link"} initialOpen={true}>
						<LinkControl
							value={at.linkValue}
							onChange={ ( nextValue ) => {
								setAttributes({ linkValue: nextValue })
							}}
						/>
					</PanelBody>

					<PanelBody title={"Prominence"} initialOpen={true}>
						<RadioControl
							label=""
							help="Changes the button colour scheme."
							selected={at.prominence}
							options={ [
								{ label: 'Primary', value: 'primary'},
								{ label: 'Secondary', value: 'secondary' },
							] }
							onChange={ ( option ) => { setAttributes( { prominence: option } ) } }
						/>
					</PanelBody>
				</InspectorControls>

			</>
		);
	},

	/**
	 * The save function
	 */
	save: ({ attributes: at }) => {

		const blockPropsSave = useBlockProps.save({
			className: "block-button " + at.prominence,
		});

		var staticUrl = at.linkValue.url;

		if (Number.isInteger(at.linkValue.id)) {//If it's a Post ID return empty url
			staticUrl = '';
		}
		var linkTarget= "_self";
		if (at.linkValue.opensInNewTab){
			linkTarget = "_blank";
		}

		return (
			<a {...blockPropsSave} href={staticUrl} target={linkTarget} rel="noopener">
				<RichText.Content className="block-button__title" value={at.buttonText} />
			</a>
		);
	},

});
