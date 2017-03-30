$(document).ready(function() {
	if($('.sc-chip-delete').length >= 1 ) {
		$( '.sc-chip-delete' ).click( function () {
			$( this ).closest( '.sc-chip' ).remove();
		} );
	}
});