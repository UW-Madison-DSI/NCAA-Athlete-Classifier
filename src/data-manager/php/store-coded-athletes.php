<?php
/******************************************************************************\
|                                                                              |
|                              store-athletes.php                              |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for fetching, storing, and saving athletes.         |
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
// data conversion functions
//

function monkLabelToTone($monkLabel) {
	$monkLabel = str_replace('Monk0', '', $monkLabel);
	$monkLabel = str_replace('Monk', '', $monkLabel);
	return $monkLabel;
}

//
// database lookup functions
//

function findAthletes($db, $tableName, $lastName, $position) {
	$lastName = str_replace("'", "''", $lastName);
	$query = "SELECT id from `$tableName` WHERE " .
		"`Athlete Name` like '%$lastName%' AND " .
		"position = '$position'";

	// perform query
	//
	try {
		$result = mysqli_query($db, $query);
		$indices = array();
		while($row = $result->fetch_assoc()) {
			$indices[] = $row['id'];
		}
		return $indices;
	} catch (\Exception $exception) {
		echo "Error: query = " . $query . "\n";
	}
}

//
// database storing functions
//

function storeValue($db, $tableName, $id, $key, $value) {

	// check for strings containing quotes
	//
	if (is_string($value)) {
		$value = str_replace("'", "''", $value);
		$query = "UPDATE $tableName SET $key = '$value' WHERE id = $id"; 
	} else {
		$query = "UPDATE $tableName SET $key = $value WHERE id = $id"; 
	}

	// perform query
	//
	try {
		$result = mysqli_query($db, $query);
		return $result;
	} catch (\Exception $exception) {
		echo "Error: query = " . $query . "\n";
		$result = null;
	}
}

function storeColors($db, $tableName, $id, $path) {

	// check for file
	//
	if (!file_exists($path)) {
		echo "Could not find $path\n";
		return;
	}

	// parse file
	//
	$json = json_decode(file_get_contents($path));

	// save values
	//
	$dominantColor = implode(',', $json->dominant_color);
	$monk_tone = monkLabelToTone($json->monk_label);
	storeValue($db, $tableName, $id, 'dominant_color', $dominantColor);
	storeValue($db, $tableName, $id, 'dominant_tone', $monk_tone);
}

function storeSkinTones($db, $tableName, $id, $path) {

	// check for file
	//
	if (!file_exists($path)) {
		echo "Could not find $path\n";
	}

	// parse file
	//
	$json = json_decode(file_get_contents($path));

	// save values
	//
	$skinColor = $json->faces[0]->skin_tone;
	$skinTone = monkLabelToTone($json->faces[0]->tone_label);
	storeValue($db, $tableName, $id, 'skin_color', $skinColor);
	storeValue($db, $tableName, $id, 'skin_tone', $skinTone);
}

function storeDemography($db, $tableName, $id, $path) {

	// check for file
	//
	if (!file_exists($path)) {
		echo "Could not find $path\n";
	}

	// parse file
	//
	$json = json_decode(file_get_contents($path))[0];
	$precision = 3;

	// save values
	//
	storeValue($db, $tableName, $id, 'race_asian', round(floatval($json->race->asian), $precision));
	storeValue($db, $tableName, $id, 'race_indian', round(floatval($json->race->indian), $precision));
	storeValue($db, $tableName, $id, 'race_black', round(floatval($json->race->black), $precision));
	storeValue($db, $tableName, $id, 'race_white', round(floatval($json->race->white), $precision));
	storeValue($db, $tableName, $id, 'race_middle_eastern', round(floatval($json->race->{'middle eastern'}), $precision));
	storeValue($db, $tableName, $id, 'race_latino_hispanic', round(floatval($json->race->{'latino hispanic'}), $precision));
	storeValue($db, $tableName, $id, 'dominant_race', $json->dominant_race);
	storeValue($db, $tableName, $id, 'face_confidence', $json->face_confidence);
}

//
// athlete storing functions
//

function storeAthlete($db, $tableName, $dirname, $playerName, $id) {

	// store image filename
	//
	$imageName = $playerName . '.jpg';
	storeValue($db, $tableName, $id, 'image_name', $imageName);

	echo "Storing athlete " . $playerName . "\n";

	// store color thief skin tones
	//
	$path = $dirname . '/Colors/' . $playerName . ' Colors.json'; 
	storeColors($db, $tableName, $id, $path);

	// store skin tones
	//
	$path = $dirname . '/Skin Tones/' . $playerName . ' Skin Tone.json'; 
	storeSkinTones($db, $tableName, $id, $path);

	// store demography
	//
	$path = $dirname . '/Demography/' . $playerName . ' Demography.json'; 
	storeDemography($db, $tableName, $id, $path);
}

function storeAthletes($db, $tableName, $dirname, $playerName) {

	// break apart player name
	//
	$terms = $playerName;
	$terms = str_replace('Jr..', 'Jr#.', $terms);
	$terms = str_replace('St.', 'St#', $terms);
	$terms = explode('.', $terms);
	for ($i = 0; $i < count($terms); $i++) {
		$terms[$i] = str_replace('#', '.', $terms[$i]);
	}

	// parse player name
	//
	$number = $terms[0];
	$lastName = $terms[1];
	$position = $terms[2];
	$university = $terms[3];

	// look up athlete
	//
	$ids = findAthletes($db, $tableName, $lastName, $position);
	if (count($ids) == 0) {
		echo "Could not find player #$number with last name = $lastName\n";
		return;
	}

	for ($i = 0; $i < count($ids); $i++) {
		$id = $ids[$i];
		storeAthlete($db, $tableName, $dirname, $playerName, $id);
	}
}

function storeAthleteDirectory($db, $tableName, $dirname) {

	// find athlete name from directory
	//
	$playerName = basename($dirname);

	// look up athlete
	//
	$ids = findAthleteByName($db, $tableName, $playerName);
	if (count($ids) == 0) {
		echo "Could not find player $playerName.\n";
		return;
	}

	echo "Storing athlete " . $playerName . "\n";

	// store image filename
	//
	for ($i = 0; $i < count($ids); $i++) {
		$id = $ids[$i];
		storeAthlete($db, $tableName, $dirname, $playerName, $id);
	}
}

//
// directory storing methods
//

function storeAthletesDirectories($db, $tableName, $dirname) {
	$basename = basename($dirname);
	$directories = [];

	// iterate over all items in directory
	//
	$iterator = new DirectoryIterator($dirname);
	foreach ($iterator as $file) {
		if (!$file->isFile()) {
			$pathname = $file->getPath();
			$filename = $file->getFilename();

			# save directory in array
			#
			if ($filename != '.' && $filename != '..' && $filename != $basename) {
				$path = $pathname . '/' . $filename;
				$directories[] = array( 
					"dirname" => $filename,
					"path" => $path
				);
			}
		}
	}

	// sort directories in ascending order
	//
	asort($directories);

	// iterate over directories
	//
	foreach ($directories as $directory) {
		storeAthleteDirectory($db, $tableName, $directory['path']);
	}
}

function storeAthletesDirectory($db, $tableName, $dirname) {
	$basename = basename($dirname);
	$images = [];

	// iterate over all items in directory
	//
	$iterator = new DirectoryIterator($dirname);
	foreach ($iterator as $file) {
		if ($file->isFile()) {
			$pathname = $file->getPath();
			$filename = $file->getFilename();
			$basename = pathinfo($filename, PATHINFO_EXTENSION);
			$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if ($extension == 'jpg') {
				$playerName = pathinfo($filename, PATHINFO_FILENAME);
				storeAthletes($db, $tableName, $dirname, $playerName);
			}
		}
	}
}

//
// main
//

/*
$id = findAthletes($db, 'players_data', 26, 'Easley', 'QB');
echo("id = " . print_r($id, 1));
exit();
*/

// check command line arguments
//
if (count($argv) < 2) {
	echo "Usage: php store_athletes.php <directory> <table_name> [-folders]\n";
	echo "The -folders option expects each image to be in a separate folder.";
	exit();
}

// parse command line arguments
//
$path = $argv[1];
$tableName = $argv[2];
$useFolders = ($argc > 3 && $argv[3] == '-folders');
echo "Storing data from " . $path . "\n";

// check whether to use separate folders for generated files
//
if ($useFolders) {
	storeAthletesDirectories($db, $tableName, $path);
} else {
	storeAthletesDirectory($db, $tableName, $path);
}