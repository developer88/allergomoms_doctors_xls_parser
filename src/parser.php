<?php
require __DIR__ . '/../vendor/autoload.php';

$file_path = $_ENV["FILE"];

// Download remote file
if ( isset($_ENV["REMOTE"]) ) {
    echo "Downloading file " . $_ENV["REMOTE"] . " to `./list.xlsx`\n";
    file_put_contents("list.xlsx", fopen($_ENV["REMOTE"], 'r'));
    $file_path = "./list.xlsx";
}

// Load Excel sheet
echo "Opening file " . $file_path . "\n";
if ( $xlsx = SimpleXLSX::parse($file_path) ) {
    save_json(parse_excel_file($xlsx), $file_path);
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
            "address" => implode(", ", array($rows[$i][0], $rows[$i][1], trim(str_replace("?", "", $rows[$i][4])) )),
            "links" => parse_links($rows[$i]),
            "speciality" => mb_convert_case(trim(str_replace("?", "", $rows[$i][2])), MB_CASE_TITLE, "UTF-8")
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

function save_json($arr, $file_path) {
    $filename = dirname(realpath($file_path)) . "/doctors.json";
    echo "Saving to json file to $filename\n";
    $arr = array("data" => $arr);
    file_put_contents($filename, json_encode($arr, JSON_UNESCAPED_UNICODE));
}




?>
