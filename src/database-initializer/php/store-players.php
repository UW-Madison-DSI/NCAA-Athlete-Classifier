<?php
/******************************************************************************\
|                                                                              |
|                               store-players.php                              |
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
		case 'number':
			return intval($value);
		case 'CMP-passing':
		case 'ATT-passing':
		case 'YDS-passing':
		case 'CMP%-passing':
		case 'AVG-passing': 
		case 'LNG-passing': 
		case 'TD-passing': 
		case 'INT-passing':
		case 'SACK-passing':
		case 'SYL-passing': 
		case 'RTG-passing':
		case 'CAR-rushing': 
		case 'YDS-rushing':
		case 'AVG-rushing':
		case 'LNG-rushing':
		case 'TD-rushing':
		case 'REC-receiving':
		case 'YDS-receiving':
		case 'AVG-receiving':
		case 'LNG-receiving': 
		case'TD-receiving':
		case 'XPM-kicking':
		case 'XPA-kicking': 
		case 'XP%-kicking':
		case 'FGM-kicking':
		case 'FGA-kicking':
		case 'FG%-kicking':
		case '1-19-kicking':
		case '20-29-kicking':
		case '30-39-kicking':
		case '40-49-kicking':
		case '50+-kicking':
		case 'LNG-kicking':
		case 'PTS-kicking':
		case 'SOLO-defense':
		case 'AST-defense':
		case 'TOT-defense':
		case 'SACK-defense':
		case 'SCKYDS-defense': 
		case 'PD-defense':
		case 'INT-defense':
		case 'YDS-defense':
		case 'LNG-defense':
		case 'TD-defense':
		case 'FF-defense':
		case 'FR-defense':
		case 'FTD-defense':
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
				$row[$name] = fieldToValue($name, $value);
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
if (count($argv) < 2) {
	echo "Usage: php store-players.php <filename> <table_name>\n";
	exit();
}

// parse command line arguments
//
$filename = $argv[1];
$tableName = $argv[2];

// store player data from file
//
if (($file = fopen($filename, "r")) !== FALSE) {
	storePlayerData($file, $db, $tableName);
	fclose($file);
} else {
	echo "File '" . $filename . "' not found. \n";
}