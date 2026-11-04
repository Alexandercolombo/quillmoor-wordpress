( function () {
	var rules = {
		visit_name: function ( v ) { return v.trim() ? '' : 'Please tell us your name.'; },
		visit_email: function ( v ) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( v.trim() ) ? '' : 'Please enter an email address we can reply to, like name@example.com.'; }
	};
	document.querySelectorAll( '.visit-form' ).forEach( function ( form ) {
		function check( input ) {
			var message = rules[ input.name ]( input.value );
			var note = document.getElementById( input.id + '-error' );
			if ( note ) { note.remove(); }
			input.removeAttribute( 'aria-invalid' );
			input.removeAttribute( 'aria-describedby' );
			if ( message ) {
				note = document.createElement( 'span' );
				note.id = input.id + '-error';
				note.className = 'field-error';
				note.textContent = message;
				input.after( note );
				input.setAttribute( 'aria-invalid', 'true' );
				input.setAttribute( 'aria-describedby', note.id );
			}
			return ! message;
		}
		Object.keys( rules ).forEach( function ( name ) {
			var input = form.elements[ name ];
			// Clear the message as they type, not on blur: on blur the page shifts under a click on Send.
			input.addEventListener( 'input', function () { if ( input.hasAttribute( 'aria-invalid' ) ) { check( input ); } } );
		} );
		form.addEventListener( 'submit', function ( e ) {
			var bad = Object.keys( rules ).map( function ( n ) { return form.elements[ n ]; } ).filter( function ( i ) { return ! check( i ); } );
			if ( bad.length ) { e.preventDefault(); bad[ 0 ].focus(); }
		} );
	} );
} )();
