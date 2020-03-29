<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            p {
                display: inline-block;
                margin-right: -110px;
            }

            input {
                width: 10%;
            }

            .submit {
                width: 4%;
            }
        </style>
        <title>Document</title>
    </head>

    <body>
        <form action="index.php" method="POST">
            Síť <input type="number" name="first" value="">
            . <input type="number" name="second" value=""> 
            . <input type="number" name="third" value="">
            . <input type="number" name="fourth" value="">
            / <input type="number" name="prefix" value="">
            <input class="submit" type="submit" name="submit" value="submit">
        </form>


        <?php
        $first = filter_input(INPUT_POST, "first");
        $second = filter_input(INPUT_POST, "second");
        $third = filter_input(INPUT_POST, "third");
        $fourth = filter_input(INPUT_POST, "fourth");
        $prefix = filter_input(INPUT_POST, "prefix");

        
            function maska($prefix) {
                $maska = null;
                for ($i = 0; $i < $prefix; $i++) {
                    $maska .= 1;
                }
                for ($prefix; $prefix < 32; $prefix++) {
                    $maska .= 0;
                }

                $arrMaska = str_split($maska, 8);

                return bindec($arrMaska[0]) . "." . bindec($arrMaska[1]) . "." . bindec($arrMaska[2]) . "." . bindec($arrMaska[3]);
            }

            function splitingOctet($prefix) {
                $octet = null;
                while ($prefix >= 0) {
                    $prefix -= 8;
                    $octet++;
                }
                return $octet;
            }

            function Network($prefix, $first, $second, $third, $fourth) {
                $return = null;
                $octet = splitingOctet($prefix);
                $pocetJednicek = $prefix % 8;

                switch ($octet) {
                    case 1: $number = $first;
                        break;
                    case 2: $number = $second;
                        $return .= $first . ".";
                        break;
                    case 3: $number = $third;
                        $return .= $first . "." . $second . ".";
                        break;
                    case 4: $number = $fourth;
                        $return .= $first . "." . $second . "." . $third . ".";
                        break;
                }
                $number = sprintf("%08d", decbin($number));

                implode(" ", $arrnumber = str_split($number));
                for ($pocetJednicek; $pocetJednicek < 8; $pocetJednicek++) {
                    $arrnumber[$pocetJednicek] = 0;
                }
                $return .= bindec(implode("", $arrnumber));

                switch ($octet) {
                    case 1: $return .= "." . "0" . "." . "0" . "." . "0";
                        break;
                    case 2: $return .= "." . "0" . "." . "0";
                        break;
                    case 3: $return .= "." . "0";
                        break;
                    case 4:
                        break;
                }
                return $return;
            }


                
                
            

            function Broadcast($prefix, $first, $second, $third, $fourth) {
                $return = null;
                $octet = splitingOctet($prefix);

                switch ($octet) {
                    case 1: $number = $first;
                        break;
                    case 2: $number = $second;
                        $return .= $first . ".";
                        break;
                    case 3: $number = $third;
                        $return .= $first . "." . $second . ".";
                        break;
                    case 4: $number = $fourth;
                        $return .= $first . "." . $second . "." . $third . ".";
                        break;
                }


                $number = sprintf("%08d", decbin($number));

                $pocetJednicek = $prefix % 8;


                implode(" ", $arrnumber = str_split($number));
                for ($pocetJednicek; $pocetJednicek < 8; $pocetJednicek++) {
                    $arrnumber[$pocetJednicek] = 1;
                }
                $return .= bindec(implode("", $arrnumber));

                switch ($octet) {
                    case 1: $return .= "." . bindec(11111111) . "." . bindec(11111111) . "." . bindec(11111111);
                        break;
                    case 2: $return .= "." . bindec(11111111) . "." . bindec(11111111);
                        break;
                    case 3: $return .= "." . bindec(11111111);
                        break;
                    case 4:
                        break;
                }
                return $return;
            }

            function First($network) {
                $arr = str_split($network);
                $arr[sizeof($arr) - 1] += 1;
                return implode("", $arr);
            }

            function Last($broadcast) {
                $arr = str_split($broadcast);
                $arr[sizeof($arr) - 1] -= 1;
                return implode("", $arr);
            }

        

        echo "<br>M: ";
        echo maska($prefix);
        echo "<br>N:";
        echo Network($prefix, $first, $second, $third, $fourth);
        echo "<br>F:";
        echo First(Network($prefix, $first, $second, $third, $fourth));
        echo "<br>L:";
        echo Last(Broadcast($prefix, $first, $second, $third, $fourth));
        echo "<br>B:";
        echo Broadcast($prefix, $first, $second, $third, $fourth);

        echo "<br># 2<sup>n</sup>-2:";
        echo pow(2, 32 - $prefix) - 2 . "<br><br>";
        
        
        
        echo "<b>BIN:</b><br>M: ";
        echo decbin(maska($prefix));
        echo "<br>N:";
        echo decbin(Network($prefix, $first, $second, $third, $fourth));
        echo "<br>F:";
        echo decbin(First(Network($prefix, $first, $second, $third, $fourth)));
        echo "<br>L:";
        echo decbin(Last(Broadcast($prefix, $first, $second, $third, $fourth)));
        echo "<br>B:";
        echo decbin(Broadcast($prefix, $first, $second, $third, $fourth));

        echo "<br># 2<sup>n</sup>-2:";
        echo decbin(pow(2, 32 - $prefix) - 2) . "<br><br>";
        ?>
    </body>

</html>