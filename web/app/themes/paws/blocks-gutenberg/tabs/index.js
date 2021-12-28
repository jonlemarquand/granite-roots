import { ToggleControl } from "@wordpress/components";
import { Icon, chevronLeft, chevronRight, closeSmall, plus } from "../../build/node_modules/@wordpress/icons"
/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { useBlockProps, InnerBlocks, RichText, InspectorControls, BlockControls } = wp.blockEditor;
const { PanelBody, Button, TextControl, Dashicon } = wp.components;
const { Component, Fragment, useState, useEffect } = wp.element;
const { registerFormatType } = wp.richText;
const { select, useSelect, dispatch } = wp.data;
const { createBlock, BlockFormatControls } = wp.blocks;
const el = wp.element.createElement;

/**
 * Tabs parent block
 */
//TODO: WHAT IF?: Two tabs block on the page, need to check for Dom manipulation in the editor

registerBlockType( 'paws/tabs', {
	title: __( 'Tabs' ),
	description: __( 'Create multiple panels of content with tabbed navigation' ),
	icon: 'table-row-after',
	category: 'design',
	apiVersion: 2,
	keywords: [
		__( 'tabs' ),
		__( 'tabbed' ),
		__( 'panels' ),
	],
	attributes: {
		blockClass: {
			type: 'string',
			default: 'block-tabs',
		},
		classAttribute: {
			type: 'string',
			default: '',
		},
		tabTitlesArray: {
			type: "array",
		},
		tabTotal: {
			type: "integer",
			default: 1,
		},
		uniqueId: {
			type: "string",
		},
		isFullwidth: {
			type: 'boolean',
			default: false
		}
	},
	edit: ({ clientId, setAttributes, attributes: at }) => {

		const [tabTitlesArray, setTabTitlesArray] = useState(false);
		const [activeTab, setActiveTab] = useState(0);
		const [tabIdTotal, setTabIdTotal] = useState(at.tabTotal);
		const [uniqueId, setUniqueId] = useState(at.uniqueId);

		useEffect(() => {
			if(at.tabTitlesArray) {
				setTabTitlesArray(at.tabTitlesArray)
			} else {
				setTabTitlesArray([
					{
						tabPosition: 0,
						tabName: null,
						tabId: `tab-0`
					}
				]);
			}
			if(!uniqueId) {
				let uniqueIdGenerated = Math.random().toString(36).substr(2, 9);
				setAttributes({uniqueId: uniqueIdGenerated});
				setUniqueId(uniqueIdGenerated);
			}
		}, []);

		useEffect(() => {
			//When the active tab is changed, change the body to match
			changeActiveTabBody(activeTab);
		}, [activeTab]);

		const updateTabNavTitle = (value) => {
			let updatedArray = tabTitlesArray;
			updatedArray[activeTab].tabName = value;

			setTabTitlesArray(updatedArray);
			setAttributes({tabTitlesArray: updatedArray});
		}

		const addTab = () => {
			//Create child Tab block
			let tabsLength = tabTitlesArray.length;
			let tabPosition = tabsLength;
			let newTabId = tabIdTotal + 1;
			let newTabIndex = `tab-${tabIdTotal}`;

			let block = wp.blocks.createBlock('paws/tab', { tabIndex: newTabIndex, uniqueId: uniqueId });
			dispatch('core/block-editor').insertBlock(block, tabIdTotal, clientId, false);

			setTabTitlesArray([...tabTitlesArray, {tabPosition: tabPosition, tabName: null, tabId: newTabIndex}]);
			setAttributes({tabTitlesArray: [...tabTitlesArray, {tabPosition: tabPosition, tabName: null, tabId: newTabIndex}]});
			setAttributes({tabTotal: newTabId});
			setTabIdTotal(newTabId);

			setActiveTab(tabPosition);
		};

		const updateTabOrder = (forward) => {
			let tabsLength = tabTitlesArray.length;

			let activeTabNumber = parseInt(activeTab);
			let newTab;

			if(forward && activeTab < tabsLength) {
				newTab = activeTabNumber + 1;
			} else if(activeTabNumber != 0) {
				newTab = activeTabNumber - 1;
			} else {
				newTab = activeTabNumber;
			}

			let oldTab = activeTab;

			let oldList = tabTitlesArray;

			let oldTitle = tabTitlesArray[activeTab].tabName;
			let newTitle = tabTitlesArray[newTab].tabName;

			oldList[oldTab].tabName = newTitle;
			oldList[newTab].tabName = oldTitle;

			let oldTabId = tabTitlesArray[activeTab].tabId;
			let newTabId = tabTitlesArray[newTab].tabId;

			oldList[oldTab].tabId = newTabId;
			oldList[newTab].tabId = oldTabId;

			setTabTitlesArray(oldList);
			setAttributes({tabTitlesArray: oldList});

			setActiveTab(newTab);
		};

		const changeActiveTabBody = (activeTabNumber) => {
			if(uniqueId && tabTitlesArray) {
				let tabId = tabTitlesArray[activeTabNumber].tabId ??= `tab-0`;
				let parentNode = document.querySelector(`.block-tabs[data-uniqueid="${uniqueId}"]`);
				let selectedTab = parentNode.querySelector(".block-tab[data-tab-index='" + tabId + "']");
				//console.log(uniqueId);
				[...parentNode.getElementsByClassName(`block-tab`)].map(x => x.setAttribute("data-tab-visible", false));
				//console.log(tabId);
				selectedTab.setAttribute("data-tab-visible", true);
			}
		}

		let blockEditor = useSelect((select) => select('core/block-editor'));

		const makeActiveTab = (e) => {

			let activeTabNumber = e.target.id;
			setActiveTab(activeTabNumber);

		};

		const deleteTab = () => {
			let tabArray = tabTitlesArray;
			let tabId = tabTitlesArray[activeTab].tabId;

			if(tabArray.length > 1) {
				//remove array object
				tabArray.splice(activeTab, 1);

				//TODO: remap values
				console.log(tabArray);
				let newTabArray = tabArray.map(tab => {

					let newTabPosition = tabArray.indexOf(tab);
					return {tabPosition: newTabPosition, tabName: tab.tabName, tabId: tab.tabId};
				});

				//set value menu
				setAttributes({tabTitlesArray: newTabArray});
				setTabTitlesArray(newTabArray);

				let newActiveTab = activeTab;
				let tabsLength = newTabArray.length;

				if(activeTab === tabsLength) {
					newActiveTab = parseInt(activeTab) - 1;
				} else {
					changeActiveTabBody(newActiveTab);
				}

				setActiveTab(newActiveTab);

				//remove child tab body element
				const innerBlockIds = blockEditor.getBlockOrder( clientId );
				let innerBlocks = [];
				innerBlockIds.forEach( ( innerBlockId ) => {
					innerBlocks.push(blockEditor.getBlock( innerBlockId ));
				} );
				let blockToRemove = innerBlocks.find(block => block.attributes.tabIndex === tabId );
				let whatResult = dispatch('core/block-editor').removeBlock(blockToRemove.clientId, false);
				//console.log(whatResult);
			}
		}

		let blockClassUpdated = at.blockClass + ' ' + at.classAttribute;
		at.isFullwidth ? blockClassUpdated += ' full-width' : '';


		const blockPropsEdit = useBlockProps( {
			className: blockClassUpdated,
			id: uniqueId,
			'data-uniqueid': uniqueId,
		} );
		if(uniqueId && tabTitlesArray) {
		return (
			<div { ...blockPropsEdit }>
				<BlockControls group="block">
					<div className="block-controls-tab-order">
						<span className="block-controls-tab-order__text">Reorder</span>
						<Button 
							className="block-controls-tab-order__button"
							icon={chevronLeft}
							label={"Move tab left"}
							disabled={activeTab == 0 ? true : false} 
							onClick={(event) => {
								event.stopPropagation();
								updateTabOrder();
							}} />
						<Button 
							className="block-controls-tab-order__button"
							icon={chevronRight}
							label={"Move tab right"}
							disabled={activeTab == tabTitlesArray.length - 1 ? true : false}  
							onClick={(event) => {
								event.stopPropagation();
								updateTabOrder(true);
							}} 
						/>
					</div>
				</BlockControls>
				<BlockControls group="inline">
						<Button
							className="block-controls-delete__button"
							icon={closeSmall}
							label={"Delete tab"}
							disabled={tabTitlesArray.length > 1 ? false : true}
							onClick={event => {
								event.stopPropagation();
								deleteTab();
							}}
						/>
				</BlockControls>
				{/* Dynamic tabs navigation */}
				<ul className="block-tabs__nav">
					{tabTitlesArray && tabTitlesArray.map(tab => (
						<RichText
							tagName="li"
							className={activeTab == tab.tabPosition ? `block-tabs__nav-item active` : `block-tabs__nav-item`}
							value={tab.tabName}
							key={tab.tabPosition}
							id={tab.tabPosition}
							allowedFormats={[]}
							placeholder="Add title here"
							onClick={makeActiveTab}
							onChange={value => {updateTabNavTitle(value)}}
						/>
					))}
					<li className="block-tabs__nav-item add-tab">
						<Button
							className={'block-editor-button-block-appender'}
							onClick={ addTab }
							label={"Add tab"}
						>
							<Icon icon={ plus } />
						</Button>
					</li>
				</ul>

				<InnerBlocks
					allowedBlocks={ ['paws/tab'] }
					template={ [
						['paws/tab', {tabIndex: `tab-0`, uniqueId: uniqueId} ],
					] }
					direction="horizontal"
					// renderAppender={ InnerBlocks.ButtonBlockAppender }
					renderAppender={false}
				/>

				<InspectorControls>
					<PanelBody title="Tabs contained">
						<ToggleControl
							label="Full width"
							checked={at.isFullwidth}
							onChange={(value) => setAttributes({ isFullwidth: value })}
						/>
					</PanelBody>
				</InspectorControls>


			</div>
		);
					} else {
						return null;
					}
	},

	/**
	 * The save function
	 */
	save: ( props ) => {

		const {
			setAttributes,
			attributes: {
				classAttribute,
				blockClass,
				isFullwidth,
				tabTitlesArray,
				uniqueId
			},
		} = props;

		let blockClassUpdated = blockClass + ' ' + classAttribute;
		isFullwidth ? blockClassUpdated += ' full-width' : '';

		const blockPropsSave = useBlockProps.save( {
			className: blockClassUpdated,
			id: uniqueId,
		} );

		return (

			<div { ...blockPropsSave }>

				{/* Tabs Navigation */}
				<ul className="block-tabs__nav">
					{tabTitlesArray && tabTitlesArray.map(tab => (
						<RichText.Content
							tagName="li"
							className={tab.tabPosition == 0 ? `block-tabs__nav-item active` : `block-tabs__nav-item`}
							value={tab.tabName}
							key={tab.tabPosition}
							id={tab.tabPosition}
							data-tab-target={tab.tabId}
							data-title={tab.tabName}
						/>

					))}
				</ul>

				{/*  Tabs Children */}
				<InnerBlocks.Content />

			</div>
		);
	},

} );

/**
 * Register the Tabs Panel child block.
 */
registerBlockType( 'paws/tab', {
	title: __( 'Tabs Panel' ),
	description: __( 'A child panel of the Tabs block.' ),
	icon: 'text',
	category: 'design',
	apiVersion: 2,
	parent: ['paws/tabs'],
	keywords: [
		__( 'tab' ),
		__( 'panel' ),
		__( 'content' ),
	],
	attributes: {
		tabIndex: {
			type: 'string',
			default: '',
		},
		tabTitle: {
			type: 'string',
			default: '',
		},
		panelHeading: {
			type: 'string',
			default: '',
		},
		uniqueId: {
			type: 'string',
			default: '',
		},
	},
	supports: {
		__experimentalParentSelector: false,
	},
	example: { // constructs a preview
	},

	/**
	 * The edit function
	 */
	edit: ( props ) => {

		const {
			setAttributes,
			attributes: {
				panelHeading,
				tabTitle,
				tabIndex,
				uniqueId,
			},
		} = props;

		const blockPropsEdit = useBlockProps( { className: `block-tab`, } );
		let tabVisible = tabIndex == `tab-0` ? true : false;

		return (

			<div {...blockPropsEdit} data-tab-index={props.attributes.tabIndex} data-tab-visible={tabVisible}>
				<div className="block-tab__content">
					<InnerBlocks template={ [['core/paragraph']]} />
				</div>
			</div>

		);
	},

	/**
	 * The save function
	 */
	save: ( props ) => {

		const blockPropsSave = useBlockProps.save( { className: `block-tab`, } );
		let tabVisible = props.attributes.tabIndex == `tab-0` ? true : false;

		return (
			<div {...blockPropsSave} data-tab-index={props.attributes.tabIndex} data-tab-visible={tabVisible}>
				<div className="block-tab__content">
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},

} );
