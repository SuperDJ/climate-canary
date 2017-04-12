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
		var to = new Date(),
			from = new Date( to - 60000*15 ),
			to = to.getFullYear()+'-'+( to.getMonth() < 10 ? '0'+to.getMonth() : to.getMonth() )+'-'+( to.getDate() < 10 ? '0'+to.getDate() : to.getDate() )+( to.getHours() < 10 ? '0'+to.getHours() : to.getHours() )+':'+( to.getMinutes() < 10 ? '0'+to.getMinutes() : to.getMinutes() )+':'+( to.getSeconds() < 10 ? '0'+to.getSeconds() : to.getSeconds() ),
			from = from.getFullYear()+'-'+( from.getMonth() < 10 ? '0'+from.getMonth() : from.getMonth() )+'-'+( from.getDate() < 10 ? '0'+from.getDate() : from.getDate() )+( from.getHours() < 10 ? '0'+from.getHours() : from.getHours() )+':'+( from.getMinutes() < 10 ? '0'+from.getMinutes() : from.getMinutes() )+':'+( from.getSeconds() < 10 ? '0'+from.getSeconds() : from.getSeconds() ),
			url = 'https://apiv1.makesenz2.nl/api/sensor/16/data/'+from+'/'+to;
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
		console.log('Token:');
		console.log( token );

		getSensors( token, function( sensors ) {
			console.log('Sensors:');
			console.log( sensors );

			getSensorData(token, function( data ) {
		   		console.log('Data:');
		   		console.log(data);
			});
		});
	});
});