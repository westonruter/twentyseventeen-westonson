/**
 * Header Transition Animations
 *
 */
window.HeaderAnimation = {};
( function( window, $, app ) {

	// Constructor
	app.init = function() {
		app.cache();

		if ( app.meetsRequirements() ) {
			app.bindEvents();
		}
	};

	// Cache all the things
	app.cache = function() {
		app.$c = {
			body: $( 'body' ),
			menuItems: $( '.main-navigation a' )
		};
	};

	// Combine all events
	app.bindEvents = function() {
		app.$c.menuItems.on( 'click touchstart',  app.doHeaderAnimation );
	};

	// Do we meet the requirements?
	app.meetsRequirements = function() {
		return app.$c.menuItems.length;
	};

	// Animate the header
	app.doHeaderAnimation = function( event ) {

        var targetLink = $(event.target);

		app.$c.body.removeClass( 'loading' );

		if ( app.$c.body.hasClass( 'twentyseventeen-front-page' ) ) {
			app.$c.body.addClass( 'loading' );
        }

        if ( targetLink.parent( 'li' ).hasClass( 'menu-item-home' ) ) {
            app.$c.body.addClass('loading homeward');
        }
	};

	// Engage
	$( app.init );

})( window, jQuery, window.HeaderAnimation );
