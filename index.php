<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>El tiempo en Almendralejo</title>
	<link rel="shortcut icon" type="image/png" href="./img/weather_icon.png"/>
	<link href="style.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Dancing+Script&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/b7104a1a86.js"></script>

	<script>
		function dayandnight(){
			var current = new Date();
			var dayTime = current.getHours();
			var element = document.getElementsByClassName("mainData")[0];

            if (dayTime > 6 && dayTime < 9) {
                element.style.backgroundImage = "url('./img/sunrise.jpg')";
            }
            else if (dayTime >= 9 && dayTime < 20) {
                element.style.backgroundImage = "url('./img/blue_sky2.jpg')";
            }
            else if (dayTime >= 20 && dayTime < 22) {
                element.style.backgroundImage = "url('./img/sunset_clouds.jpg')";
            }
            else {
                element.style.backgroundImage = "url('./img/night_moon.jpg')";
            }
		}
	</script>
</head>
<body>

<!-- Navigation -->
<nav id ="navbarid" class="navbar navbar-expand-md navbar-light bg-light navigation-bar">
	<div class="container-fluid">
		<a class="navbar-brand" href=""><img src="./img/weather_icon.png" alt="Logo"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item-active">
					<h4 class="logoText">El tiempo en Almendralejo</h4>
				</li>
			</ul>
		</div>
	</div>
</nav>

<div id="alert-panel" class="container-fluid-padding">
	<div class="row text-center padding alert-content">
		<div class="col-xs-12 col-md-6">
			<p id="alert-msg"></p>
		</div>
		<div class="col-xs-12 col-md-6">
			<i id="alert-icon"></i>
		</div>
	</div>
</div>


<?php 

require_once 'vendor/autoload.php';

$client = new InfluxDB\Client('localhost', '8086');

// fetch the database
$database = $client->selectDB('arduino_test_bueno');

// executing a query will yield a resultset object
// Retrieving the newest reading
$result = $database->query('select temperature, humidity, wind_speed, rain, co2ppm, 
pressure from arduinoData order by desc limit 1');

// get the points from the resultset yields an array
$points = $result->getPoints();

$cont = 0;

foreach ($points as $value) {
	foreach ($value as $item) {
		if ($cont == 1) {
			$temp = $item;
		}
		if ($cont == 2) {
			$hum = $item;
		}
		if ($cont == 3) {
			$wind = $item;
		}
		if ($cont == 4) {
			$rain = $item;
		}
		if ($cont == 5) {
			$co2 = $item;
		}
		if ($cont == 6) {
			$pressure = $item;
		}
		$cont = $cont + 1;
	}
}
?>

<div class="container-fluid-padding mainData">
	<div class="row text-center padding rows">
		<!-- Temperatura -->
		<div class="col-xs-4 col-md-2">
			<i id="tempIcon" class="fas fa-thermometer-half"></i>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4>Temperatura</h4>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4 id="tem"></h4>
		</div>
		<!-- Humedad -->
		<div class="col-xs-4 col-md-2">
			<i class="fas fa-tint"></i>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4>Humedad</h4>
		</div>
		<div class="col-xs-4 col-md-2 ">
			<h4 id="hum"></h4>
		</div>
		<div class="col-12">
			<hr class="light">
		</div>
			<!-- Viento -->
		<div class="col-xs-4 col-md-2">
			<i class="fas fa-wind"></i>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4>Viento</h4>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4 id="wind"></h4>
		</div>
			<!-- Lluvia -->
		<div class="col-xs-4 col-md-2">
			<i class="fas fa-cloud-showers-heavy"></i>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4>Lluvia</h4>
		</div>
		<div class="col-xs-4 col-md-2 rain">
			<h4 id="rain"></h4>
		</div>
		<div class="col-12">
			<hr class="light">
		</div>
			<!-- CO2 -->
		<div class="col-xs-4 col-md-2">
			<i class="fas fa-seedling"></i>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4>CO2</h4>
		</div>
		<div class="col-xs-4 col-md-2 co2">
			<h4 id="co2"></h4>
		</div>
			<!-- Presion atmosferica -->
		<div class="col-xs-4 col-md-2">
			<i class="fab fa-cloudscale"></i>
		</div>
		<div class="col-xs-4 col-md-2">
			<h4>Presion</h4>
		</div>
		<div class="col-xs-4 col-md-2 pressure">
			<h4 id="pressure"></h4>
		</div>
	</div>
</div>

<!--- Footer -->
<footer>
	<div class="container-fluid padding">
		<div class="row text-center">
			<div class="col-md-4">
				<img src="./img/Escudo.png" alt="Escudo">
				<hr class="light">
				<h4>Ayuntamiento de Almendralejo</h4>
			</div>
			<div class="col-md-4">
				<hr class="light">
				<h4>Contacto</h4>
				<hr class="light"> 
				<p>Teléfono: 924 67 05 07</p> 
				<p>Fax: 924 67 11 49</p> 
				<p>Email: oac@almendralejo.es</p> 
			</div>
			<div class="col-md-4">
				<hr class="light">
				<h4>Realizado por</h4>
				<hr class="light">
				<p>Sección de Informática y Nuevas Tecnologías del Ayuntamiento de Almendralejo</p>
			</div>
			<div class="col-12">
				<hr class="light">
				<h5>&copy; Todos los derechos reservados. </h5>
			</div>
		</div>
	</div>
</footer>
</body>

<script type="text/javascript">
	dayandnight();
</script>

<script type="text/javascript">
	// InfluxDB newest reading
	var temperature = "<?php echo $temp ?>"; 
	var humidity = "<?php echo $hum ?>";
	var wind_speed = "<?php echo $wind ?>";
	var rain = "<?php echo $rain ?>";
	var co2ppm = "<?php echo $co2 ?>";
	var pressure = "<?php echo $pressure ?>";

	// State icon
	//<i class="fas fa-exclamation-triangle"></i>

	// Data conversion
	wind_speed = parseFloat(wind_speed).toFixed(2);
	pressure = pressure/100;

	var highTempAlert = "no";
	var lowTempAlert = "no";
	var windAlert = "no";
	var rain1hAlert = "no";
	// var rain12hAlert = "no";

	/* Alertas basadas en:  
	*
	* http://www.aemet.es/documentos/es/eltiempo/prediccion/avisos/plan_meteoalerta/plan_meteoalerta.pdf
	* 
	* - Zona: Barros y Serena Badajoz
	* - Codigo: 700603            
	* - Alerta:                       Amarilla           Naranja           Roja
	* - temperaturas máximas:  			38 					40 				44
	* - temperaturas mínimas: 			-4 					-8 			   -12
	* - racha máxima: 					70 					90 			   130
	* - precipitación 12 h: 			40 					80 			   120
	* - precipitación 1 h: 				15 					30 				60
	* - nieve 24 h:						 2 					 5 				20
	*/
	
	if (temperature >= 38) {
		// Amarilla
		highTempAlert = "yellow";
		if (temperature >= 40) {
			// Naranja
			highTempAlert = "orange";
			if (temperature >= 44) {
			// Roja
			highTempAlert = "red";
			}
		}
	}

	if (temperature <= -4) {
		lowTempAlert = "yellow";
		if (temperature <= -8) {
			lowTempAlert = "orange";
			if (temperature <= -12)	
				lowTempAlert = "red";
		}
	}

	if (wind_speed > 70) {
		windAlert = "yellow";
		if (wind_speed > 90) {
			windAlert = "orange";
			if (win_speed > 130)
				windAlert = "red";
		}
	}

	if (rain > 15) {
		rain1hAlert = "yellow";
		if (rain > 30) {
			rain1hAlert = "orange";
			if (rain > 60)	
				rain1hAlert = "red";
		}
	}
		
	// Test
	//highTempAlert = "yellow";
	//lowTempAlert = "yellow";
	//windAlert = "yellow";	
	//rain1hAlert = "yellow";

	if (highTempAlert != "no" || lowTempAlert != "no" || windAlert != "no" || rain1hAlert != "no") {
		
		var panel = document.getElementById("alert-panel");
		var alertMsg = document.getElementById("alert-msg");
		var alertIcon = document.getElementById("alert-icon");

		panel.style.display = "block";
		panel.style.border = "1px solid black";

		alertMsg.style.fontWeight = "900";
		alertMsg.style.color = "#333";
		alertMsg.style.fontSize = "20px";

		alertIcon.style.fontSize = "25px";
		alertIcon.style.color = "#333";	

		if (highTempAlert != "no") {
			alertIcon.className = 'fas fa-sun';

			if (highTempAlert == "yellow") {
				panel.style.background = "rgba(211, 186, 5, 1)";
				alertMsg.textContent = "Alerta amarilla por altas temperaturas.";
				panel.style.animation = "fadeinoutYellow 4s linear forwards infinite";
			}

			if (highTempAlert == "orange") {
				panel.style.background = "rgba(195, 115, 25, 1)";
				alertMsg.textContent = "Alerta naranja por altas temperaturas.";
				panel.style.animation = "fadeinoutOrange 4s linear forwards infinite";
			}

			if (highTempAlert == "red") {
				alertMsg.style.color = "#F8F8F8";
				alertIcon.style.color = "#F8F8F8";
				panel.style.background = "rgba(250, 4, 4, 1)";
				alertMsg.textContent = "Alerta roja por altas temperaturas.";
				panel.style.animation = "fadeinoutRed 4s linear forwards infinite";
			}
		}

		if (lowTempAlert != "no") {
			alertIcon.className = 'far fa-snowflake';

			if (lowTempAlert == "yellow") {
				panel.style.background = "rgba(211, 186, 5, 1)";
				alertMsg.textContent = "Alerta amarilla por bajas temperaturas.";
				panel.style.animation = "fadeinoutYellow 4s linear forwards infinite";
			}

			if (lowTempAlert == "orange") {
				panel.style.background = "rgba(195, 115, 25, 1)";
				alertMsg.textContent = "Alerta naranja por bajas temperaturas.";
				panel.style.animation = "fadeinoutOrange 4s linear forwards infinite";
			}

			if (lowTempAlert == "red") {
				alertMsg.style.color = "#F8F8F8";
				alertIcon.style.color = "#F8F8F8";
				panel.style.background = "rgba(250, 4, 4, 1)";
				alertMsg.textContent = "Alerta roja por bajas temperaturas.";
				panel.style.animation = "fadeinoutRed 4s linear forwards infinite";
			}
		}

		if (rain1hAlert != "no") {
			alertIcon.className = 'fas fa-cloud-showers-heavy';

			if (rain1hAlert == "yellow") {
				panel.style.background = "rgba(211, 186, 5, 1)";
				alertMsg.textContent = "Alerta amarilla por lluvias.";
				panel.style.animation = "fadeinoutYellow 4s linear forwards infinite";
			}

			if (rain1hAlert == "orange") {
				panel.style.background = "rgba(195, 115, 25, 1)";
				alertMsg.textContent = "Alerta naranja por lluvias.";
				panel.style.animation = "fadeinoutOrange 4s linear forwards infinite";
			}

			if (rain1hAlert == "red") {
				alertMsg.style.color = "#F8F8F8";
				alertIcon.style.color = "#F8F8F8";
				panel.style.background = "rgba(250, 4, 4, 1)";
				alertMsg.textContent = "Alerta roja por lluvias.";
				panel.style.animation = "fadeinoutRed 4s linear forwards infinite";
			}
		}

		if (windAlert != "no") {
			alertIcon.className = 'fas fa-wind';

			if (windAlert == "yellow") {
				panel.style.background = "rgba(211, 186, 5, 1)";
				alertMsg.textContent = "Alerta amarilla por viento.";
				panel.style.animation = "fadeinoutYellow 4s linear forwards infinite";
			}

			if (windAlert == "orange") {
				panel.style.background = "rgba(195, 115, 25, 1)";
				alertMsg.textContent = "Alerta naranja por viento.";
				panel.style.animation = "fadeinoutOrange 4s linear forwards infinite";
			}

			if (windAlert == "red") {
				alertMsg.style.color = "#F8F8F8";
				alertIcon.style.color = "#F8F8F8";
				panel.style.background = "rgba(250, 4, 4, 1)";
				alertMsg.textContent = "Alerta roja por viento.";
				panel.style.animation = "fadeinoutRed 4s linear forwards infinite";
			}
		}
	}

	document.getElementById("tem").textContent = temperature + " ºC";
	document.getElementById("hum").textContent = humidity + " %";
	document.getElementById("wind").textContent = wind_speed + " km/h";
	document.getElementById("rain").textContent = rain + " ml/h";
	document.getElementById("co2").textContent = co2ppm + " ppm";
	document.getElementById("pressure").textContent = pressure + " hPa";

</script>

</html>
