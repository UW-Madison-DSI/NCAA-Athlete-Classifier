<?php
/******************************************************************************\
|                                                                              |
|                          store-4-universities.php                            |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for reading player data from a CSV file             |
|        and storing it in a database.                                         |
|                                                                              |
|        Author(s): Abe Megahed                                                |
|                                                                              |
|        This file is subject to the terms and conditions defined in           |
|        'LICENSE.txt', which is part of this source code distribution.        |
|                                                                              |
|******************************************************************************|
|  Copyright (C) 2024 Data Science Institute, Univeristy of Wisconsin-Madison  |
\******************************************************************************/

include "models/athlete.php";
include 'utilities/database.php';

//
// functions
//

function fieldToValue($name, $value) {
	switch ($name) {
		case 'JERSEY':
		case 'WEIGHT':
		case 'AGE':
		case 'Skin Tone':
			return intval($value);
		case 'RATING':
			return floatval($value);
		default:
			return $value;
	}
}

function storePlayerData($file, $db, $tableName) {
	$info = [];
	$count = 0;
	$names = [];
	while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
		if ($count == 0) {
			$names = $data;
		} else {
			$row = [];
			for ($i = 0; $i < count($names); $i++) {
				$name = $names[$i];
				$value = $data[$i] != ''? $data[$i] : 'NULL';
				if ($name != '') {
					$row[$name] = fieldToValue($name, $value);
				}
			}

			// add row
			//
			insert($db, $tableName, $row);
		}
		$count++;
	}
}

//
// main
//

// check command line arguments
//
if (count($argv) < 1) {
	echo "Usage: php store-4-universities.php <filename>\n";
	exit();
}

// parse command line arguments
//
$filename = $argv[1];

// store player data from file
//
if (($file = fopen($filename, "r")) !== FALSE) {
	storePlayerData($file, $db, '4_universities');
	fclose($file);
} else {
	echo "File '" . $filename . "' not found. \n";
}