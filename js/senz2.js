$(document).ready(function() {
	var credentials = {
			username: 'v.v.rambaran@st.hanze.nl',
			password: 'climatecanary',
			client_id: '2_115y6b025c740ssw4c4w0k0s8o00g0wsg4ggo4coc4g0o48ckk',
			client_secret: 'uxyjgknjsys4ow0swockcc0g0gsgckcco0co4ks8owg4o8g4w',
			grant_type: 'password'
		};

	/**
	 * Get access token
	 *
	 * @param credentials
	 * @param fn
	 *
	 * @return access token
	 */
	function getToken( credentials, fn ) {
		var url = 'https://apiv1.makesenz2.nl/oauth/v2/token';

		$.post( url, credentials, function( data ) {
				fn(data);
				return true;
		});
		return false;
	}

	/**
	 * Get all sensors
	 *
	 * @param fn
	 *
	 */
	function getSensors( token, fn ) {
		var url = 'https://apiv1.makesenz2.nl/api/customer/3/sensors';

		$.ajax({
			url: url,
			headers: {'Authorization': 'Bearer '+token.access_token}
		}).done( function( sensors ) {
			fn( sensors );
			return true;
		});
		return false;
	}

	function getSensorData( token, fn ) {
		var id = '20',
			minutes = 30,
			to = new Date(),
			from = new Date( to - 60000 * minutes ),
			to = to.getUTCFullYear() + '-' +
				( ( to.getUTCMonth() + 1 ) < 10 ? '0' + ( to.getUTCMonth() + 1 ) : ( to.getUTCMonth() + 1 ) ) + '-' +
				( to.getUTCDate() < 10 ? '0' + to.getUTCDate() : to.getUTCDate() ) +
				( to.getUTCHours() < 10 ? '0' + to.getUTCHours() : to.getUTCHours() ) + ':' +
				( to.getUTCMinutes() < 10 ? '0' + to.getUTCMinutes() : to.getUTCMinutes() ) + ':' +
				( to.getUTCSeconds() < 10 ? '0' + to.getUTCSeconds() : to.getUTCSeconds() ),
			from = from.getUTCFullYear() + '-' +
				( ( from.getUTCMonth() + 1 ) < 10 ? '0' + ( from.getUTCMonth() + 1) : ( from.getUTCMonth() + 1 ) ) + '-' +
				( from.getUTCDate() < 10 ? '0' + from.getUTCDate() : from.getUTCDate() ) +
				( from.getUTCHours() < 10 ? '0' + from.getUTCHours() : from.getUTCHours() ) + ':' +
				( from.getUTCMinutes() < 10 ? '0' + from.getUTCMinutes() : from.getUTCMinutes() ) + ':' +
				( from.getUTCSeconds() < 10 ? '0'+from.getUTCSeconds() : from.getUTCSeconds() ),
			url = 'https://apiv1.makesenz2.nl/api/sensor/'+ id +'/data/' + from + '/' + to;

		/*console.log('to date:');
		console.log(to);
		console.log('form date');
		console.log(from);*/

		$.ajax({
			url: url,
			headers: {'Authorization': 'Bearer '+token.access_token},
		}).done( function( data ) {
			fn(data);
			return true;
		});
		return false;
	}

	getToken( credentials, function( token ) {
		/*console.log('Token:');
		console.log( token );*/

		getSensors( token, function( sensors ) {
			/*console.log('Sensors:');
			console.log( sensors );*/

			getSensorData(token, function( data ) {
		   		/*console.log('Data:');
		   		console.log(data);*/
		   		// Insert sensor data in database
			});
		});
	});
});