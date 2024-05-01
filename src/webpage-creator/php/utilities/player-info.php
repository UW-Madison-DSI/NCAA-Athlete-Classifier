<?php
/******************************************************************************\
|                                                                              |
|                               player-info.php                                |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for loading player information.                     |
|                                                                              |
|        Author(s): Abe Megahed                                                |
|                                                                              |
|        This file is subject to the terms and conditions defined in           |
|        'LICENSE.txt', which is part of this source code distribution.        |
|                                                                              |
|******************************************************************************|
|  Copyright (C) 2024 Data Science Institute, Univeristy of Wisconsin-Madison  |
\******************************************************************************/

//
// functions
//

function parsePlayerInfo($file) {
	$info = [];
	while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
		$num = count($data);
		$number = $data[0];
		$firstName = $data[1];
		$lastName = $data[2];
		$team = $data[3];
		$position = $data[4];
		$height = $data[5];
		$weight = $data[6];
		$class = $data[7];
		$birthplace = $data[8];
		$seasonYear = $data[9];
		$photoUrl = $data[10];
		$name = $data[11];
		$id = $lastName . '.' . $position . '.' . $team;

		$info[$id] = [
			'number' => $number,
			'first_name' => $firstName,
			'last_name' => $lastName,
			'team' => $team,
			'position' => $position,
			'height' => $height,
			'weight' => $weight,
			'class' => $class,
			'birthplace' => $birthplace,
			'season_year' => $seasonYear,
			'photo_url' => $photoUrl,
			'name' => $name
		];
	}
	return $info;
}

function getTeams($playerInfo) {
	$teams = [];
	foreach ($playerInfo as $key => $info) {
		$team = $info['team'];
		if (!in_array($team, $teams)) {
			array_push($teams, $team);
		}
	}
	return $teams;
}

//
// lookup functions
//

function getPlayerInfo($id) {
	global $playerInfo;
	if ($playerInfo) {
		if (array_key_exists($id, $playerInfo)) {
			return $playerInfo[$id];
		}
	}
}

function schoolToTeam($school) {
	global $teams;
	for ($i = 0; $i < count($teams); $i++) {
		$team = $teams[$i];
		if (str_contains($team, $school)) {
			return $team;
		}
	}
}

//
// variables
//

$filename = '../../../data/Coding/players_data_final.csv';

// open file
//
if (($file = fopen($filename, "r")) !== FALSE) {

	// parse data from file
	//
	$playerInfo = parsePlayerInfo($file);
	$teams = getTeams($playerInfo);
	fclose($file);
} else {
	echo "File '" . $filename . "' not found. \n";
}