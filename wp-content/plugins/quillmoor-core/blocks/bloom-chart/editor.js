( function ( wp ) {
	var el = wp.element.createElement;
	var be = wp.blockEditor;
	var c = wp.components;
	wp.blocks.registerBlockType( 'quillmoor/bloom-chart', {
		edit: function ( props ) {
			var rows = props.attributes.rows;
			function update( i, patch ) {
				props.setAttributes( { rows: rows.map( function ( r, n ) { return n === i ? Object.assign( {}, r, patch ) : r; } ) } );
			}
			return el( 'div', be.useBlockProps(),
				el( be.InspectorControls, null,
					rows.map( function ( r, i ) {
						return el( c.PanelBody, { title: r.name || 'New plant', key: i, initialOpen: false },
							el( c.TextControl, { label: 'Plant', value: r.name, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, onChange: function ( v ) { update( i, { name: v } ); } } ),
							el( c.TextControl, { label: 'Botanical name', value: r.latin, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, onChange: function ( v ) { update( i, { latin: v } ); } } ),
							el( c.TextControl, { label: 'Months (1 to 12, with commas)', value: r.months, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, onChange: function ( v ) { update( i, { months: v } ); } } ),
							el( c.SelectControl, { label: 'Color', value: r.season, __next40pxDefaultSize: true, __nextHasNoMarginBottom: true, options: [ { label: 'April green', value: 'april' }, { label: 'July boxwood', value: 'july' }, { label: 'October gold', value: 'october' }, { label: 'January plum', value: 'january' } ], onChange: function ( v ) { update( i, { season: v } ); } } ),
							el( c.Button, { variant: 'link', isDestructive: true, onClick: function () { props.setAttributes( { rows: rows.filter( function ( x, n ) { return n !== i; } ) } ); } }, 'Remove this plant' )
						);
					} ),
					el( c.PanelBody, { title: 'Add', initialOpen: true }, el( c.Button, { variant: 'secondary', onClick: function () { props.setAttributes( { rows: rows.concat( [ { name: '', latin: '', months: '', season: 'july' } ] ) } ); } }, 'Add a plant' ) )
				),
				el( wp.serverSideRender, { block: 'quillmoor/bloom-chart', attributes: props.attributes } )
			);
		},
		save: function () { return null; }
	} );
} )( window.wp );
