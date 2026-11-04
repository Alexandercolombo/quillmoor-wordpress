( function ( wp ) {
	var el = wp.element.createElement;
	var useEntityProp = wp.coreData.useEntityProp;
	var useSelect = wp.data.useSelect;
	var Panel = wp.editor.PluginDocumentSettingPanel;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;

	function GardenPanel() {
		var type = useSelect( function ( s ) { return s( 'core/editor' ).getCurrentPostType(); }, [] );
		var meta = useEntityProp( 'postType', type, 'meta' );
		var values = meta[ 0 ] || {};
		function field( key, label, help ) {
			return el( TextControl, {
				label: label, help: help, value: values[ key ] || '', __next40pxDefaultSize: true, __nextHasNoMarginBottom: true,
				onChange: function ( v ) { var n = {}; n[ key ] = v; meta[ 1 ]( Object.assign( {}, values, n ) ); }
			} );
		}
		return el( Panel, { name: 'quillmoor-garden', title: 'Garden details' },
			field( 'garden_town', 'Town' ),
			field( 'garden_size', 'Size', 'For example: Half an acre' ),
			field( 'garden_plant', 'Signature plant', 'The botanical name, shown in italics' ),
			field( 'garden_best', 'Best month', 'For example: Best in October' ),
			el( SelectControl, {
				label: 'Season color', value: values.garden_season || 'july', __next40pxDefaultSize: true, __nextHasNoMarginBottom: true,
				options: [ { label: 'April green', value: 'april' }, { label: 'July boxwood', value: 'july' }, { label: 'October gold', value: 'october' }, { label: 'January plum', value: 'january' } ],
				onChange: function ( v ) { meta[ 1 ]( Object.assign( {}, values, { garden_season: v } ) ); }
			} )
		);
	}
	wp.plugins.registerPlugin( 'quillmoor-garden', { render: GardenPanel } );
} )( window.wp );
