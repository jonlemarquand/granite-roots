
/*
 * Image Picker Component -- See params
 * - Usage 1: <ImagePicker />
 * - Usage 2: <ImagePickerPanelBody />
 * - Usage 3: <ImagePickerPreview />
 */
import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/blockEditor';
import { PanelBody, Button } from '@wordpress/components';

export const ImagePicker = ({ setAttributes, imageUrlAttribute, imageAltAttribute, extraClass}) => {
	let componentClass = 'component-image-picker';

	return (
		<div className={extraClass ? componentClass + ' ' + extraClass : componentClass}>
			{imageUrlAttribute ? //Has image
				<MediaUploadCheck>
					<div className={componentClass + "__media-wrapper"}>
						<img className={componentClass + "__media"} src={imageUrlAttribute} alt={imageAltAttribute} />
					</div>
					<EditImageButtons componentClass={componentClass} setAttributes={setAttributes} replaceText='Replace' removeText='Remove' />
				</MediaUploadCheck>
				: //No image
				<UploadImageButton componentClass={componentClass} setAttributes={setAttributes} />
			}
		</div>
	)
};

/*
* Image Picker Panel Body
* Used for Inspector Controls
*/
export const ImagePickerPanelBody = ({ setAttributes, imageUrlAttribute, imageAltAttribute }) => {
	return(
		<PanelBody title={'Image'} initialOpen={true}>
			<ImagePicker setAttributes={setAttributes} imageUrlAttribute={imageUrlAttribute} imageAltAttribute={imageAltAttribute} />
		</PanelBody>
	)
}

/*
* Image Picker Preview
* Used for image inside block in the edit function
*/

export const ImagePickerPreview = ({ setAttributes, imageUrlAttribute, imageAltAttribute, blockClass, imageClass}) => {
	let componentClass           = 'component-image-picker-preview';
	let imageElementClass        = '';
	let imageElementWrapperClass = ''

	if(imageClass){//Image class
		imageElementClass        = blockClass + imageClass;
		imageElementWrapperClass = blockClass + imageClass + '-wrapper';

	} else if(blockClass){//Block class
		imageElementClass        = blockClass + '__image';
		imageElementWrapperClass = blockClass + '__image-wrapper';
	}

	return (
		<div className={imageElementWrapperClass ? componentClass + ' ' + imageElementWrapperClass : componentClass}>
			{imageUrlAttribute ? //Has image
				<MediaUploadCheck>
					<img className={imageElementClass ? imageElementClass : ''} src={imageUrlAttribute} alt={imageAltAttribute} />
					<EditImageButtons componentClass={componentClass} setAttributes={setAttributes} replaceText='' removeText='' />
				</MediaUploadCheck>
			: //No image
				<UploadImageButton componentClass={componentClass} setAttributes={setAttributes}  />
			}
		</div>
	)
};

// ============= Image Controls
export const EditImageButtons = ({ componentClass, setAttributes, replaceText, removeText }) => {
	return (
		<MediaUpload
			title={'Icon'}
			onSelect={(media) => updateImageAttr(setAttributes, media)}
			allowedTypes={['image']}
			render={({ open }) => (
				<div className={componentClass + "__edit-buttons"}>
					<Button className={componentClass + "__edit-button"} isSecondary onClick={open} icon="images-alt2">
						{replaceText}
					</Button>
					<Button className={componentClass + "__edit-button"} isDestructive onClick={(media) => updateImageAttr(setAttributes, false)} icon="remove">
						{removeText}
					</Button>
				</div>
			)}
		/>
	)
}

export const UploadImageButton = ({ componentClass, setAttributes }) => {
	return (
		<MediaUploadCheck>
			<MediaUpload
				onSelect={(media) => updateImageAttr(setAttributes, media)}
				render={({ open }) => {
					return (
						<Button onClick={open} className={componentClass + "__upload-button"} isSecondary icon="images-alt2">
							{'Choose from Media Library'}
						</Button>
					);
				}}
			/>
		</MediaUploadCheck>
	)
}

const updateImageAttr = (
	setAttributes,
	imageObject = false,
	imageUrlAttribute = 'imageUrl',
	imageAltAttribute = 'imageAlt'
) => {
	let newImageUrl = imageObject ? imageObject.sizes.full.url : ''
	let newImageAlt = imageObject ? imageObject.alt : ''

	setAttributes({
		[imageUrlAttribute]: newImageUrl,
		[imageAltAttribute]: newImageAlt
	});
};

