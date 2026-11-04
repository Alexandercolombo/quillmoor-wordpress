( function () {
	var months = { april: [ 2, 3, 4 ], july: [ 5, 6, 7 ], october: [ 8, 9, 10 ], january: [ 11, 0, 1 ] };
	document.querySelectorAll( '[data-season-strip]' ).forEach( function ( root ) {
		var tabs = [].slice.call( root.querySelectorAll( '[role=tab]' ) );
		var imgs = root.querySelectorAll( '.window img' );
		function set( id, focus ) {
			[ 'april', 'july', 'october', 'january' ].forEach( function ( s ) {
				root.classList.toggle( 'season-' + s, s === id );
				document.body.classList.toggle( 'accent-' + s, s === id );
			} );
			tabs.forEach( function ( t ) {
				var on = t.dataset.season === id;
				t.setAttribute( 'aria-selected', on );
				t.tabIndex = on ? 0 : -1;
				if ( on && focus ) { t.focus(); }
			} );
			imgs.forEach( function ( i ) {
				i.classList.toggle( 'on', i.dataset.season === id );
				if ( i.dataset.season === id ) { i.loading = 'eager'; }
			} );
		}
		// Open on the season it is outside right now.
		var m = new Date().getMonth(), today = 'july';
		Object.keys( months ).forEach( function ( k ) { if ( months[ k ].indexOf( m ) > -1 ) { today = k; } } );
		set( today );

		// One slow pass through the year on load, so visitors see the strip is alive. Any touch stops it.
		var timer, order = tabs.map( function ( t ) { return t.dataset.season; } );
		var start = order.indexOf( today ), step = 0;
		function stop() { clearInterval( timer ); }
		if ( ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			imgs.forEach( function ( i ) { i.loading = 'eager'; } );
			window.setTimeout( function () {
				timer = window.setInterval( function () {
					step++;
					set( order[ ( start + step ) % order.length ] );
					if ( step >= order.length ) { stop(); }
				}, 1500 );
			}, 1200 );
			[ 'pointerdown', 'keydown' ].forEach( function ( ev ) { root.addEventListener( ev, stop ); } );
		}
		tabs.forEach( function ( t, i ) {
			t.addEventListener( 'click', function () { set( t.dataset.season ); } );
			t.addEventListener( 'keydown', function ( e ) {
				var d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
				if ( d ) { e.preventDefault(); set( tabs[ ( i + d + tabs.length ) % tabs.length ].dataset.season, true ); }
			} );
		} );
		var strip = root.querySelector( '.strip' ), down = false;
		strip.addEventListener( 'pointerdown', function () { down = true; } );
		window.addEventListener( 'pointerup', function () { down = false; } );
		strip.addEventListener( 'pointermove', function ( e ) {
			if ( ! down ) { return; }
			var el = document.elementFromPoint( e.clientX, e.clientY );
			var b = el && el.closest( '[role=tab]' );
			if ( b && b.getAttribute( 'aria-selected' ) !== 'true' ) { set( b.dataset.season ); }
		} );
	} );
} )();
