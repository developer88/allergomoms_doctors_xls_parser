<?php
require __DIR__ . '/../vendor/autoload.php';

// Load Excel sheet
echo "Opening file " . $_ENV["FILE"] . "\n";
if ( $xlsx = SimpleXLSX::parse($_ENV["FILE"]) ) {
    save_json(parse_excel_file($xlsx));
} else {
	echo SimpleXLSX::parseError();
}

function parse_excel_file($file) {
    //$header = $file->rows()[0];
    // [0] => Регион
    // [1] => Город
    // [2] => Специальность
    // [3] => ФИО
    // [4] => Место работы
    // [5] => Дополнительно
    // [6] => Instagram
    // [7] => Facebook
    //
    echo "Parsing file\n";
    $rows = $file->rows();
    $as_json = array();
    for ($i=1; $i < count($rows); $i++) { 
        array_push($as_json, array(
            "name" => $rows[$i][3],
            "address" => implode(", ", array($rows[$i][0], $rows[$i][1], $rows[$i][4])),
            "links" => parse_links($rows[$i])
        )); 
    }

    return $as_json;
}

function parse_links($arr) {
    $links = array();
    if (strlen($arr[5]) > 0 ) { array_push($links, array('url' => $arr[5], 'type' => 'website')); };
    if (strlen($arr[6]) > 0 ) { array_push($links, array('url' => $arr[6], 'type' => 'instagram')); };
    if (strlen($arr[7]) > 0 ) { array_push($links, array('url' => $arr[7], 'type' => 'facebook')); };

    return $links;
}

function save_json($arr) {
    $filename = dirname(realpath($_ENV["FILE"])) . "/doctors.json";
    echo "Saving to json file to $filename\n";
    $arr = array("data" => $arr);
    file_put_contents($filename, json_encode($arr, JSON_UNESCAPED_UNICODE));
}




?>
