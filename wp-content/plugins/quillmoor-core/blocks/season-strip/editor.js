( function ( wp ) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	wp.blocks.registerBlockType( 'quillmoor/season-strip', {
		edit: function ( props ) {
			var seasons = props.attributes.seasons;
			function update( i, patch ) {
				props.setAttributes( { seasons: seasons.map( function ( s, n ) { return n === i ? Object.assign( {}, s, patch ) : s; } ) } );
			}
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null, seasons.map( function ( s, i ) {
					return el( c.PanelBody, { title: s.label, key: s.id, initialOpen: i === 0 },
						el( be.MediaUploadCheck, null, el( be.MediaUpload, {
							allowedTypes: [ 'image' ], value: s.imageId,
							onSelect: function ( m ) { update( i, { imageId: m.id, alt: m.alt || s.alt } ); },
							render: function ( o ) { return el( c.Button, { variant: 'secondary', onClick: o.open }, s.imageId ? 'Replace photo' : 'Choose photo' ); }
						} ) ),
						el( c.TextControl, { label: 'Season name', value: s.label, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, onChange: function ( v ) { update( i, { label: v } ); } } ),
						el( c.TextControl, { label: 'Plant (botanical name)', value: s.plant, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, onChange: function ( v ) { update( i, { plant: v } ); } } ),
						el( c.TextControl, { label: 'Photo description', value: s.alt, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, onChange: function ( v ) { update( i, { alt: v } ); } } )
					);
				} ) ),
				el( wp.serverSideRender, { block: 'quillmoor/season-strip', attributes: props.attributes } )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
