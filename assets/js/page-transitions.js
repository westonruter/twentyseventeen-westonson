window.addEventListener( 'click', function( e ) {
	var node = e.target;
	var homeUrl = window.wpPageTransitions.homeUrl;

	while ( null !== node ) {

		// Search for any external links.
		if ( node.href && 0 !== node.href.replace( document.location.href, '' ).indexOf( '#' ) ) {

			// If the link has been already clicked, prevent subsequent clicks.
			if ( document.body.classList.contains( 'page-transition' ) ) {
				e.preventDefault();
				e.stopPropagation();
				return false;
			}

			var href = node.href;

			// Remove trailing slash.
			if ( href.lastIndexOf( '/' ) === href.length - 1 ) {
				href = href.substr( 0, href.length - 1 );
			}

			// Scroll back to top if redirecting to a template with a different header height.
			if ( href === homeUrl || document.body.classList.contains( 'twentyseventeen-front-page' ) ) {
				window.scrollTo( 0, 0 );
			}

			// Add transition related classes to the BODY tag.
			document.body.classList.add( 'page-transition' );
			if ( href === homeUrl ) {
				document.body.classList.add( 'home-page-transition' );
			}

			return;
		}

		node = node.parentNode;
	}
});
