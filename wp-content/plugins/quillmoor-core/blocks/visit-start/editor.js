( function ( wp ) {
	wp.blocks.registerBlockType( 'quillmoor/visit-start', {
		edit: function ( props ) {
			return wp.element.createElement( 'div', wp.blockEditor.useBlockProps(), wp.element.createElement( wp.serverSideRender, { block: 'quillmoor/visit-start' } ) );
		},
		save: function () { return null; }
	} );
} )( window.wp );
