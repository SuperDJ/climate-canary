$(document).ready(function() {
	navigator.geolocation.getCurrentPosition(initialize);
});

var $from = document.getElementById('from'),
	$to = document.getElementById('to'),
	$fromLat = document.getElementById('fromLat'),
	$fromLng = document.getElementById('fromLng'),
	$toLat = document.getElementById('toLat'),
	$toLng = document.getElementById('toLng'),
	$autocomplete;

function initialize(location) {
	// Get current location coords
	var $location = {lat: location.coords.latitude, lng: location.coords.longitude},
		$geocoder = new google.maps.Geocoder;

	// Set from coords
	$fromLat.value = location.coords.latitude;
	$fromLng.value = location.coords.longitude;

	// Set current location
	$geocoder.geocode({'location': $location}, function(results, status) {
		if (status === 'OK') {
			if (results[1]) {
				$from.value = results[1].formatted_address;
			} else {
				window.alert('No results found');
			}
		} else {
			window.alert('Geocoder failed due to: ' + status);
		}
	});
}

function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	$autocomplete = new google.maps.places.Autocomplete(
		($to),
		{types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	$autocomplete.addListener('place_changed', fillInAddress);
}
initAutocomplete();

function fillInAddress() {
	// Get the place details from the autocomplete object.
	var $place = $autocomplete.getPlace();
	$toLat.value = $place.geometry.location.lat();
	$toLng.value = $place.geometry.location.lng();
}