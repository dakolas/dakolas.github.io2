<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>El tiempo en Almendralejo</title>
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

			//dayTime = 7;

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
<nav class="navbar navbar-expand-md navbar-light bg-light">
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
			<i class="fas fa-thermometer-half"></i>
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

	// Data conversion
	wind_speed = parseFloat(wind_speed).toFixed(2);
	pressure = pressure/100;

	document.getElementById("tem").textContent = temperature + " ºC";
	document.getElementById("hum").textContent = humidity + " %";
	document.getElementById("wind").textContent = wind_speed + " km/h";
	document.getElementById("rain").textContent = rain + " ml/h";
	document.getElementById("co2").textContent = co2ppm + " ppm";
	document.getElementById("pressure").textContent = pressure + " hPa";

</script>

</html>
