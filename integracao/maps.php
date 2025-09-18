<pre>
	<?php
	$latitude = "-30.0323787";
	$longitude = "-51.2317719";
	$endereco = reverse_geocode($_GET["latitude"], $_GET["longitude"]);

	echo $endereco->results[0]->address_components[2]->long_name."<br>";
	print_r($endereco);
				
	function reverse_geocode($lat, $lon) {
		$url = "https://maps.google.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false&key=AIzaSyAsXR0Vkk6iDwLFNotURsgchRiul4Phqak";
		$data = json_decode(file_get_contents($url));
		if (!isset($data->results[0]->address_components)){
			return "erro";
		}else{
			return $data;
		}
	}
	?>
</pre>