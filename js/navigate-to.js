$(document).ready(function() {
	/**
	 * Get current location
	 */
	function getCurrentCoords(fn) {
		$.post('https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyAB9kYP7clJyhX45mt6y3LobeKA9L6ivNo').done(function(success) {
			var $obj = {lat: success.location.lat, lng: success.location.lng};
			fn($obj);
		})
		.fail(function(err) {
			console.log("API Geolocation error! \n\n"+err);
			return false;
		});
	}

	/**
	 * Get coords from address
	 *
	 * @param $address
	 */
	function getCoords($address, fn) {
		var $coords;
		$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+$address+'&key=AIzaSyAB9kYP7clJyhX45mt6y3LobeKA9L6ivNo').done(function($data) {
			$coords = $data.results[0].geometry.location;

			if( !empty($coords) ) {
				fn($coords);
			} else {
				return false;
			}
		});
	}

	/**
	 * Get Address from coords
	 */
	function getAddress($coords, fn) {
		var $address;
		$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?latlng='+$coords.lat+','+$coords.lng+'&key=AIzaSyAB9kYP7clJyhX45mt6y3LobeKA9L6ivNo').done(function($data) {
			$address = $data.results[0].formatted_address;

			if( !empty($address) ) {
				fn($address);
			} else {
				return false;
			}
		});
	}

	// Set current Address
	getCurrentCoords( function(e) {
		getAddress( e, function ( a ) {
			// Set data in form fields
			$('#from').val(a);
			$('#fromLat').val(e.lat);
			$('#fromLng').val(e.lng);

			if( $('.index').length >= 1 ) {
				$('')
			}

			// Create url for each saved address
			$('.address').each(function() {
				var $this = $(this),
					$url = $this.attr('href');

				if( $url != '#' ) {
					$url = $url.replace('fAddress', a);
					$url = $url.replace('fLat', e.lat);
					$url = $url.replace('fLng', e.lng);

					$this.attr('href', $url);
				}
			});
		} );
	});

	//console.log($fLat);
	function getRouteData( $fLat, $fLng, $tLat, $tLng, fn ) {
		$.getJSON('https://maps.googleapis.com/maps/api/directions/json?origin='+$fLat+','+$fLng+'&destination='+$tLat+','+$tLng+'&key=AIzaSyAB9kYP7clJyhX45mt6y3LobeKA9L6ivNo').done(function($data) {
			console.log($data);
		});
	}


});

var $to = document.getElementById('to'),
	$toLat = document.getElementById('toLat'),
	$toLng = document.getElementById('toLng'),
	$autocomplete;

function initialize(location) {
	// Get current location coords
	var $geocoder = new google.maps.Geocoder;

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
	console.log($place);
	console.log($place.geometry.location.lat());
	console.log($place.geometry.location.lng());
	$toLat.value = $place.geometry.location.lat();
	$toLng.value = $place.geometry.location.lng();

	console.log($toLat.value);
	console.log($toLng.value);
}

