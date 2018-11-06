window.addEventListener( 'wp-amp-app-shell-navigate', ( event ) => {
	console.info( event.type, event.detail );
} );
window.addEventListener( 'wp-amp-app-shell-load', ( event ) => {
	console.info( event.type, event.detail );
} );
window.addEventListener( 'wp-amp-app-shell-ready', ( event ) => {
	console.info( event.type, event.detail );
} );

window.addEventListener( 'wp-amp-app-shell-navigate', function( event ) {
	var homeUrl = window.wpPageTransitions.homeUrl;
	var currentUrl = event.detail.url.href;

	// If the link has been already clicked, prevent subsequent clicks.
	if ( document.body.classList.contains( 'page-transition' ) ) {
		return;
	}

	// Remove trailing slash.
	if ( currentUrl.lastIndexOf( '/' ) === currentUrl.length - 1 ) {
		currentUrl = currentUrl.substr( 0, currentUrl.length - 1 );
	}

	// Scroll back to top if redirecting to a template with a different header height.
	if ( currentUrl === homeUrl || document.body.classList.contains( 'twentyseventeen-front-page' ) ) {
		window.scrollTo( 0, 0 );
	}

	// Add transition related classes to the BODY tag.
	document.body.classList.add( 'page-transition' );
	if ( currentUrl === homeUrl ) {
		document.body.classList.add( 'home-page-transition' );
	}
});
