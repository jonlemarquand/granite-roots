/**
 * Custom container-width image block
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/blockEditor';
import { useSelect } from '@wordpress/data';
import { ImagePickerPreview, ImagePickerPanelBody } from '../_components/ImagePicker';

const { siteDomain } = pawsVars; // vars passed from enqueue_backend.php

registerBlockType('paws/image', {
	title: 'Image',
	icon: <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/></svg>,
	description: 'Full container-width image, resized but uncropped.',
	category: 'media',
	apiVersion: 2,
	keywords: [
		__( 'image' ),
		__( 'full-width' ),
	],
	example: {
		attributes: {
			imageUrl: pawsVars.templateDirectoryUri + '/assets/images/placeholder.jpg',
		},
	},
	attributes: {
		imageUrl: {
			type: "string",
			default: ''
		},
		imageAlt: {
			type: "string",
			default: ''
		},
		parentBlockName: {
			type: "string",
			default: ''
		}
	},

	edit: ({ setAttributes, attributes: at, clientId }) => {

		////// set parentBlockName attribute (used in images.php for resizing)
		// parent is always an array
		let parentBlockArray = useSelect((select) => select('core/block-editor').getBlockParents(clientId));

		// check if array has items
		if (parentBlockArray.length) {
			// immediate parent is the last item in the array
			let lastItem = parentBlockArray[parentBlockArray.length - 1];
			let lastItemName = useSelect((select) => select('core/block-editor').getBlockName(lastItem));

			setAttributes({ parentBlockName: lastItemName })
		}

		// get resized image in editor (images.php handles this for frontend)
		const imageOptionsString = 'images/width=1600,height=1600,crop=1';
		var imageUrlMinusBase = at.imageUrl.replace(siteDomain+'/app/uploads','');// remove base and directory
		var resizedImageUrl = imageOptionsString+imageUrlMinusBase;
		resizedImageUrl = siteDomain+'/'+resizedImageUrl;// add domain base back in

		const blockPropsEdit = useBlockProps( {
			className: "image",
		} );

		return (
			<div {...blockPropsEdit}>

				<ImagePickerPreview
					setAttributes={setAttributes}
					imageUrlAttribute={resizedImageUrl}
					imageAltAttribute={at.imageAlt}
				/>

				<InspectorControls>
					<ImagePickerPanelBody
						setAttributes={setAttributes}
						imageUrlAttribute={resizedImageUrl}
						imageAltAttribute={at.imageAlt}
					/>
				</InspectorControls>

			</div>
		);
	},

	save: ({ attributes: at }) => {

		const blockPropsSave = useBlockProps.save( {
			className: "image",
		} );

		return (
			<div { ...blockPropsSave }>
				{ at.imageUrl &&
					<img src={at.imageUrl} alt={at.imageAlt} />
				}
			</div>
		);
	}
});
