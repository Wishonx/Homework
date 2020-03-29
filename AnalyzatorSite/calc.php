<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>

	<form method="POST">
		<input type="text" name="adress" value="192.168.1.0" placeholder="Zadejte IPv4 adresu" required> / <input type="text" name="prefix" value="24" placeholder="Zadejte Prefix" required>
		<input type="submit" value="Vypočítat">
	</form>

	<?php

	$adress = explode(".", filter_input(INPUT_POST, "adress"));
	$prefix = filter_input(INPUT_POST, "prefix");


	if (count($adress) != 4) {
		echo "Zadali jste neplatnou adresu";
		$adress = array("192", "168", "0", "1");
	} else {
		for ($x = 0; $x < 4; $x++) {
			if ($adress[$x] > 256 || $adress[$x] < 0) {
				echo "Zadali jste neplatnou adresu";
				$adress = array("192", "168", "0", "1");
				break;
			}
		}
	}

	if ($prefix < 0 || $prefix > 32) {
		echo "Zadali jste neplatný prefix";
	}

	$f = 0;

	foreach ($adress as $value) {
		$bin[] = sprintf("%08d",decbin($adress[$f]));
		$f++;
		echo "<br>";
	}
	echo "<br>";

	function mask($prefix)
	{
		$p = NULL;
		for ($i = 0; $i < $prefix; $i++) {
			$p .= "1";
		}

		for ($i = 0; $i < 32 - $prefix; $i++) {
			$p .= "0";
		}
		$mask = str_split($p, 8);

		return ($mask);
	}
	$mask = mask($prefix);

	
	function network($mask, $bin){

		for($i = 0; $i <= 3; $i++){
			$network[$i] = $bin[$i] & $mask[$i];
		}
		return ($network);

	}
	$network = network($mask, $bin);


	function broadcast($prefix, $network){
		$broadcast = implode("",$network);
		$broadcast = str_split($broadcast, 1);

		for ($i = 31; $i >= 31 - ( 31 - $prefix); $i--){
			$broadcast[$i] = 1;
		}

		$broadcast = implode("",$broadcast);
		$broadcast = str_split($broadcast, 8);
		$broadcast = implode(".",$broadcast);
		return ($broadcast);
		
	}

	$broadcast = broadcast($prefix, $network);
	$broadcast = explode(".",$broadcast);
	$broadcast = implode("",$broadcast);

	function broadcastDec($broadcast){
		$bdec = str_split($broadcast, 8);
		for($i = 0; $i < 4; $i++){
			$bdec[$i] = bindec($bdec[$i]);
		}
		$bdec = implode(".",$bdec);
		return ($bdec);
	}

	function firstHost($network){
		$first = implode("",$network);
		$first[31] = 1;
		$first = str_split($first, 8);
		return ($first);		
	}
	$first = firstHost($network);

	function fhDec($first){
		for($i = 0; $i < 4; $i++){
			$fhdec[$i] = bindec($first[$i]);
		}
		$fhdec = implode(".", $fhdec);
		return ($fhdec);
	}
	

	function lastHost($broadcast){
		$last = $broadcast;
		$last[31] = 0;
		$last = str_split($last, 8);
		return ($last);
		
	}

	function hosts($prefix){
		$hosts = pow(2, 32 - $prefix)-2;

		return ($hosts);
	}


	
	?>
</body>

</html>