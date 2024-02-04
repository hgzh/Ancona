/**
 * == filter ==
 * filter tables, lists by input
 *
 * (C) 2023 Hgzh
 */
	
const getRelevantElems = $target => {
	const targetType = $target.prop( 'tagName' );

	// get relevant filter element by target type
	let relevantElem = '';
	switch ( targetType ) {
		case 'TABLE': relevantElem = 'tbody tr'; break;
		case 'DIV':   relevantElem = '> div'; break;
		case 'OL':
		case 'UL':    relevantElem = 'li'; break;
	}

	return $target.find( relevantElem );
}

const disablePagination = ( $target, state ) => {
	let $pagination = $( '.anc-pagination[data-anc-target="' + $target.attr( 'id' ) + '"]:not([data-anc-hidden="1"])' );

	// return if no pagination controllers found
	if ( !$pagination.length ) {
		return
	}

	// enable/disable pagination controller
	if ( state === true ) {
		$pagination.hide();
	} else {
		$pagination.show();

		// trigger event to update pagination
		$pagination.find( 'ul .active a' ).trigger( 'click' );
	}
}

const filterInputEvent = $obj => {
	// filter target
	const targetId = $obj.parent().attr( 'data-anc-target' );
	const $target  = $( '#' + targetId );
	const value    = $obj.val().toLowerCase();

	// empty value means nothing to filter for
	if ( value === '' ) {
		resetFilter( $target );
	} else {
		filterTarget( $target, value );
	}
}	

const resetFilter = ( $target, value ) => {
	let $children = getRelevantElems( $target );

	// display children
	$children.each( function () {
		$( this ).show();
	} );

	// display pagination
	disablePagination( $target, false );
}

const filterTarget = ( $target, value ) => {
	let $children = getRelevantElems( $target );
	let childCount = $children.length;
	let hiddenCount = 0;

	// hide children if filter text not found
	$children.each( function () {
		const text = $( this ).text().toLowerCase();
		if ( text.indexOf( value ) > -1 ) {
			$( this ).show();
		} else {
			$( this ).hide();
			hiddenCount++;
		}
	} );

	// if every child is hidden, display a message
	$( '.anc-filter-empty' ).remove();		
	if ( childCount === hiddenCount ) {
		$( '<div class="anc-filter-empty text-body-secondary text-center">Keine Einträge</div>' ).prependTo( $target );
	}

	// hide pagination
	disablePagination( $target, true );
}

export function run() {
	// add input handlers
	$( '.anc-filter input' ).on( 'input', event => {
		filterInputEvent( $( event.target ) );
	} );
}