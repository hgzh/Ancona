/**
 * == tooltip ==
 * activate Bootstrap's tooltips
 *
 * (C) 2024 Hgzh
 */

export function run() {
	let ttTriggerList = [].slice.call( document.querySelectorAll( '[data-bs-toggle="tooltip"]' ) );
	let ttList = ttTriggerList.map( ttTriggerEl => {
		return new bootstrap.Tooltip( ttTriggerEl );
	} );
}