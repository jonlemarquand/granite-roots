// add class name attributes to core paragraph, header and list blocks

const setExtraPropsToBlockType = (props, blockType, attributes) => {
    const notDefined = (typeof props.className === 'undefined' || !props.className) ? true : false

    if (blockType.name === 'core/heading') {
        return Object.assign(props, {
            className: notDefined ? `wp-block-heading` : `wp-block-heading ${props.className}`,
        });
    }

    if (blockType.name === 'core/list') {
        return Object.assign(props, {
            className: notDefined ? `wp-block-${props.tagName}` : `wp-block-${props.tagName} ${props.className}`
        });
    }

    if (blockType.name === 'core/paragraph') {
        return Object.assign(props, {
            className: notDefined ? 'wp-block-paragraph' : `wp-block-paragraph ${props.className}`,
        });
    }

    return props;
};

wp.hooks.addFilter(
    'blocks.getSaveContent.extraProps',
    'paws/block-filters',
    setExtraPropsToBlockType
);