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
	#----------------------------------- ADRESS

	$f = 0;

	foreach ($adress as $value) {
		$bin[] = sprintf("%08d",decbin($adress[$f]));
		$f++;
    }
    
    $adress_bin = implode(".", $bin);
    for($i = 0; $i < 4; $i++){
        $adress_dec[$i] = bindec($bin[$i]);
    }
    $adress_dec = implode(".",$adress_dec);

	#------------------------------------ MASKA

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
    $mask_bin = implode(".", $mask);
    for($i = 0; $i < 4; $i++){
        $mask_dec[$i] = bindec($mask[$i]);
    }
    $mask_dec = implode(".",$mask_dec);
	#--------------------------------------- NETWORK
	
	function network($mask, $bin){

		for($i = 0; $i <= 3; $i++){
			$network[$i] = $bin[$i] & $mask[$i];
		}
		return ($network);

	}
    $network = network($mask, $bin);
    $network_bin = implode(".", $network);
    for($i = 0; $i < 4; $i++){
        $network_dec[$i] = bindec($network[$i]);
    }
    $network_dec = implode(".",$network_dec);
	#--------------------------------------- BROADCAST

	function broadcast($prefix, $network){
		$broadcast = implode("",$network);
		$broadcast = str_split($broadcast, 1);

		for ($i = 31; $i >= 31 - ( 31 - $prefix); $i--){
			$broadcast[$i] = 1;
		}

		$broadcast = implode("",$broadcast);
		$broadcast = str_split($broadcast, 8);
		return ($broadcast);
		
	}

    $broadcast = broadcast($prefix, $network);
    $broadcast_bin = implode(".", $broadcast);
    for($i = 0; $i < 4; $i++){
        $broadcast_dec[$i] = bindec($broadcast[$i]);
    }
    $broadcast_dec = implode(".",$broadcast_dec);
	
	#--------------------------------------- FIRST
	function firstHost($network){
		$first = implode("",$network);
		$first[31] = 1;
		$first = str_split($first, 8);
		return ($first);		
	}
	$first = firstHost($network);

	$first_bin = implode(".", $first);
    for($i = 0; $i < 4; $i++){
        $first_dec[$i] = bindec($first[$i]);
    }
    $first_dec = implode(".",$first_dec);
	
	#--------------------------------------- LAST
	function lastHost($broadcast){
        $broadcast = implode("",$broadcast);
		$last = $broadcast;
		$last[31] = 0;
		$last = str_split($last, 8);
		return ($last);
		
    }
    $last = lastHost($broadcast);

    $last_bin = implode(".", $last);
    for($i = 0; $i < 4; $i++){
        $last_dec[$i] = bindec($last[$i]);
    }
    $last_dec = implode(".",$last_dec);
	#--------------------------------------- HOSTS
	function hosts($prefix){
		$hosts = pow(2, 32 - $prefix)-2;

		return ($hosts);
    }
    $host_count = hosts($prefix);
	#--------------------------------------- OUTPUTS
    echo "<br><br>";
    echo "Adresa: <br>" . $adress_bin . "<br>" . $adress_dec . "<br><br>";
    echo "Maska: <br>" . $mask_bin . "<br>" . $mask_dec . "<br><br>";
    echo "Network: <br>" . $network_bin . "<br>" . $network_dec . "<br><br>";
    echo "Broadcast: <br>" . $broadcast_bin . "<br>" . $broadcast_dec . "<br><br>";
    echo "První host: <br>" . $first_bin . "<br>" . $first_dec . "<br><br>";
    echo "Poslední host: <br>" . $last_bin . "<br>" . $last_dec . "<br><br>";
    echo "Počet hostů: <br>" . $host_count . "<br>";
	
	?>
</body>

</html>