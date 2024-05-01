<?php
/******************************************************************************\
|                                                                              |
|                          store-players-cleaned.php                           |
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
		case 'cmp_passing':
		case 'att_passing':
		case 'yds_passing':
		case 'cmp_percent_passing':
		case 'avg_passing': 
		case 'lng_passing': 
		case 'td_passing': 
		case 'int_passing':
		case 'sack_passing':
		case 'syl_passing': 
		case 'rtg_passing':
		case 'car_rushing': 
		case 'yds_rushing':
		case 'avg_rushing':
		case 'lng_rushing':
		case 'td_rushing':
		case 'rec_receiving':
		case 'yds_receiving':
		case 'avg_receiving':
		case 'lng_receiving': 
		case'td_receiving':
		case 'xpm_kicking':
		case 'xpa_kicking': 
		case 'xp_percent_kicking':
		case 'fgm_kicking':
		case 'fga_kicking':
		case 'fg_percent_kicking':
		case 'lng_kicking':
		case 'pts_kicking':
		case 'solo_defense':
		case 'ast_defense':
		case 'tot_defense':
		case 'sack_defense':
		case 'sckyds_defense': 
		case 'pd_defense':
		case 'int_defense':
		case 'yds_defense':
		case 'lng_defense':
		case 'td_defense':
		case 'ff_defense':
		case 'fr_defense':
		case 'ftd_defense':
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
				if ($name != '') {
					$value = $data[$i] != ''? $data[$i] : 'NULL';
					if ($value == 'NA') {
						$value = 0;
					}
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
if (count($argv) < 2) {
	echo "Usage: php store-players-cleaned.php <filename> <table_name>\n";
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