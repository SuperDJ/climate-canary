$(document).ready(function() {
	if( $('.sc-dialog').length >= 1 ) {
		var $dialog = $('.sc-dialog');

		$dialog.after('<div class="sc-dialog-background"></div>');
	}
});