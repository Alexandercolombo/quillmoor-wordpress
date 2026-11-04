( function ( wp ) {
	wp.blocks.registerBlockType( 'quillmoor/visit-form', {
		edit: function ( props ) {
			return wp.element.createElement( 'div', wp.blockEditor.useBlockProps(), wp.element.createElement( wp.serverSideRender, { block: 'quillmoor/visit-form' } ) );
		},
		save: function () { return null; }
	} );
} )( window.wp );
