/**
 * == moduleLoader ==
 * load modules by content
 *
 * (C) 2024 Hgzh
 */

(() => {
	'use strict'
	
	// autoPagination
	function loadModules() {
		if ( $( '.anc-pagination' ).length ) {
			import( './autoPagination.js' ).then( ( autoPagination ) => {;
				autoPagination.run();
			} );
		}

		// filter
		if ( $( '.anc-filter' ).length ) {
			import( './filter.js' ).then( ( filter ) => {;
				filter.run();
			} );
		}
	}
	
	loadModules();
	
})()