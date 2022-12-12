<?php

// Read kandis.csv into array
$csv = array_map('str_getcsv', file('kandis.csv'));
//  array(16) {
//     [0]=>
//     string(36) "d874f0a3-afff-4d54-a6ab-ceb2e0a5982b"
//     [1]=>
//     string(6) "Bisher"
//     [2]=>
//     string(7) "Leandra"
//     [3]=>
//     string(9) "Columberg"
//     [4]=>
//     string(5) "Uster"
//     [5]=>
//     string(1) "2"
//     [6]=>
//     string(55) "Studentin Rechtswissenschaften, Geschäftsführerin DJZ"
//     [7]=>
//     string(119) "Für eine konsequente, solidarische Politik, Chancengleichheit in der Bildung und gesellschaftliche Teilhabe für alle."
//     [8]=>
//     string(10) "1999-10-16"
//     [9]=>
//     string(4) "1999"
//     [10]=>
//     string(7) "sie/ihr"
//     [11]=>
//     string(39) "https://www.instagram.com/leandrafiona/"
//     [12]=>
//     string(42) "https://www.facebook.com/leandra.columberg"
//     [13]=>
//     string(33) "https://twitter.com/LeandraColumb"
//     [14]=>
//     string(6) "NOTSET"
//     [15]=>
//     string(6) "NOTSET"
//   }

$kandis = [];

foreach ($csv as $row) :

$kandi = [
    "id" => $row[0],
    "bisher" => ($row[1] == "Bisher") ? true : false ,
    "url" => str_replace(" ", "-", strtolower($row[2] . "-" . $row[3])),
    "vorname" => $row[2],
    "nachname" => $row[3],
    "wahlkreis" => $row[4],
    "listenplatz" => $row[5],
    "beruf" => $row[6],
    "quote" => $row[7],
    "geburtstag" => [
        "tag" => date("d", strtotime($row[8])),
        "monat" => date("m", strtotime($row[8])),
        "jahr" => date("Y", strtotime($row[8]))
    ],
    "pronomen" => ($row[10] != "NOTSET") ? $row[10] : "" ,
    "instagram" => ($row[11] != "NOTSET") ? $row[11] : "" ,
    "facebook" => ($row[12] != "NOTSET") ? $row[12] : "" ,
    "twitter" => ($row[13] != "NOTSET") ? $row[13] : "" ,
    "tiktok" => ($row[14] != "NOTSET") ? $row[14] : "" ,
    "homepage" => ($row[15] != "NOTSET") ? $row[15] : "",
    "wknr" => $row[16]
];

if (file_exists(__DIR__ . "/../public/img/kandis/" . strtolower($kandi["vorname"]) . "-" . strtolower($kandi["nachname"]) . ".jpg")) {
    rename(__DIR__ . "/../public/img/kandis/" . strtolower($kandi["vorname"]) . "-" . strtolower($kandi["nachname"]) . ".jpg", __DIR__ . "/../public/img/kandis/" . $kandi["id"] . ".jpg");
} elseif (file_exists(__DIR__ . "/../public/img/kandis/" . $kandi["id"] . ".jpg")) {
} else {
    echo "File not found: " . __DIR__ . "/../public/img/kandis/" . strtolower($kandi["vorname"]) . "-" . strtolower($kandi["nachname"]) . ".jpg" . PHP_EOL;
}
$kandi["bild"] = $kandi["id"] . ".jpg";

$kandis[$kandi["url"]] = $kandi;
endforeach;


file_put_contents(__DIR__ . "/kandis.json", json_encode($kandis, JSON_PRETTY_PRINT));