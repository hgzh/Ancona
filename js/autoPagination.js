/**
 * == autoPagination ==
 * automatically create pagination for tables, lists
 *
 * (C) 2023 Hgzh
 */
	
const paginationClickEvent = $obj => {
	// page to select		
	const selectedPage = Number( $obj.attr( 'data-anc-page' ) );

	// find pagination target
	const $controller = $obj.parents( '.anc-pagination' );
	const targetId    = $controller.attr( 'data-anc-target' );

	switchPages( targetId, selectedPage );
}

const switchPages = ( target, page ) => {
	const $controller = $( '.anc-pagination[data-anc-target="' + target + '"]' );

	// hide or show elements by selected page
	$( '#' + target + ' [data-anc-page]' ).each( function() {
		let currentPage = Number( $( this ).attr( 'data-anc-page' ) );
		if ( currentPage === page ) {
			$( this ).show();
		} else {
			$( this ).hide();
		}
	} );		

	// change controller
	$controller.each( function() {
		const pageCount = $( this ).find( '.page-item a' ).length;

		// change active item in controller
		$( this ).find( '.page-item.active' ).removeClass( 'active' );
		$( this ).find( '.page-item a[data-anc-page="' + page + '"]' ).parent().addClass( 'active' );

		// skip pages to avoid very long controller
		if ( pageCount > 7 ) {
			// show/hide skip fields
			if ( page > 4 ) {
				$( this ).find( '.anc-skip-start' ).show();
			} else {
				$( this ).find( '.anc-skip-start' ).hide();					
			}
			if ( page < pageCount - 3 ) {
				$( this ).find( '.anc-skip-end' ).show();
			} else {
				$( this ).find( '.anc-skip-end' ).hide();					
			}

			// iterate through fields
			$( this ).find( '.page-item a' ).each( function() {
				let currentPage = Number( $( this ).attr( 'data-anc-page' ) );
				if ( ( currentPage >= ( page - 2 ) && currentPage <= ( page + 2 ) )
					|| ( page < 5 && currentPage < 8 )
					|| ( page > pageCount - 4 && currentPage > ( pageCount - 7 ) )
					|| currentPage === 1
					|| currentPage === pageCount ) {
					$( this ).show();
				} else {
					$( this ).hide();
				}
			} );

		}
	} );
}

const processPaginationTarget = ( index, obj ) => {
	// find pagination controller
	const targetId   = obj.id;
	let $target      = $( '#' + targetId );
	let $controller  = $( '.anc-pagination[data-anc-target="' + targetId + '"]' );
	const targetType = $target.prop( 'tagName' );

	// return if no controller found
	if ( !$controller.length ) {
		return;
	}

	// get pagebreak count
	const pageBreak = Number( $controller.attr( 'data-anc-elements' ) );

	// get relevant pagination element by target type
	let relevantElem = '';
	switch ( targetType ) {
		case 'TABLE': relevantElem = 'tr'; break;
		case 'DIV':   relevantElem = '> div'; break;
		case 'OL':
		case 'UL':    relevantElem = 'li'; break;
	}

	// attach page to every child of target
	const childSelector = '#' + targetId + ' ' + relevantElem + ':not(.anc-pagination-skip)';
	let currentPage = 1;
	let itemCount   = 0;
	$( childSelector ).each( function() {
		$( this ).attr( 'data-anc-page', currentPage );
		itemCount++;
		if ( itemCount === pageBreak ) {
			currentPage++;
			itemCount = 0;
		}
	} );

	// if only one page, hide pagination controllers
	if ( currentPage === 1 ) {
		$controller.hide();
		$controller.attr( 'data-anc-hidden', 1 );
	} else {
		$controller.show();
		$controller.attr( 'data-anc-hidden', 0 );
	}

	// update pagination controllers with count of pages
	$controller.each( function() {
		let $pagination = $( this ).find( 'ul.pagination' );

		$pagination.empty();

		for ( let i = 1; i <= currentPage; i++ ) {

			// add hidden skip field
			if ( i === 2 || i === currentPage - 1 ) {
				let $item = $( '<li>' )
				.addClass( 'page-item disabled' )
				.appendTo( $pagination );
				let $link = $( '<span>' )
				.addClass( 'page-link' )
				.text( '…' )
				.appendTo( $item );
				if ( i === 2 ) {
					$item.addClass( 'anc-skip-start' );
				} else {
					$item.addClass( 'anc-skip-end' );						
				}
				$item.hide();
			}

			let $item = $( '<li>' )
			.addClass( 'page-item' )
			.appendTo( $pagination );
			let $link = $( '<a>' )
			.addClass( 'page-link' )
			.attr( 'data-anc-page', i )
			.attr( 'href', '#' )
			.text( i )
			.appendTo( $item );
		}
	} );

	// switch to first page
	switchPages( targetId, 1 );
}

export function run() {
	// process pagination targets
	$( '.anc-pagination-target' ).each( processPaginationTarget );

	// add click handlers
	$( '.anc-pagination a' ).on( 'click', event => {
		event.preventDefault();
		paginationClickEvent( $( event.target ) );
	} );
}