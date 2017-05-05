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

	setInterval( function() {
		getToken( credentials, function( token ) {
			/*console.log('Token:');
			console.log( token );*/

			getSensors( token, function( sensors ) {
				/*console.log('Sensors:');
				console.log( sensors );*/

				getSensorData(token, function( data ) {
					console.log('Data:');
					console.log(data);

					var humidity = 0,
						humidityDate = '',
						co2 = 0,
						co2Date = '',
						temp = 0,
						tempDate = '';

					// Only store last value
					for( var i = 0; i < data.length; i++ ) {
						// Check if value isn't empty
						if( data[i].d_humidity != null ) {
							humidityDate = data[i].event_datetime;
							humidity = data[i].d_humidity;
						}

						if( data[i].d_co2 != null ) {
							co2Date = data[i].event_datetime;
							co2 = data[i].d_co2;
						}

						if( data[i].d_temperature != null ) {
							tempDate = data[i].event_datetime;
							temp = data[i].d_temperature;
						}

					/*	console.log(data[i]);
						console.log(data[i].d_humidity);*/
					}
					/*console.log(humidity);
					console.log(co2);
					console.log(temp);*/

					// Send data to be inserted in db
					var url = '/climate-canary/core/ajax.php?co2='+co2+'&co2Date='+co2Date+'&temp='+temp+'&tempDate='+tempDate+'&humidity='+humidity+'&humidityDate='+humidityDate;

					$.get( url, function( data ) {
						console.log(data);
					});
				});
			});
		});
	}, 5000);
});