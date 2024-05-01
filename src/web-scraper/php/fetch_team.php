<?php
/******************************************************************************\
|                                                                              |
|                                fetch_team.php                                |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for fetching, storing, and saving a team.           |
|                                                                              |
|        Author(s): Abe Megahed                                                |
|                                                                              |
|        This file is subject to the terms and conditions defined in           |
|        'LICENSE.txt', which is part of this source code distribution.        |
|                                                                              |
|******************************************************************************|
|  Copyright (C) 2024 Data Science Institute, Univeristy of Wisconsin-Madison  |
\******************************************************************************/

include "models/team.php";

//
// methods
//

/**
 * Fetch a team and save it to the file system and database.
 *
 * @param string $league - The league of the team to fetch.
 * @param string $id - The id of the team to fetch.
 * @param int $year - The year of the team to fetch.
 * @param string $teamsDir - The directory name to save team data.
 * @return App\Models\Team
 */
function fetchTeam(string $league, string $id, int $year, string $directory) { 

	// create team
	//
	$team = new Team([
		'league' => $league,
		'id' => $id,
		'year' => $year
	]);

	// fetch team
	//
	$team->fetch();

	// create team directory
	//
	if (!file_exists($directory)) {
		mkdir($directory);
	}

	// save team to storage
	//
	echo "saving team to " . $directory . "\n";
	$team->save($directory);

	return $team;
}

//
// main
//

// check command line arguments
//
if (count($argv) < 3) {
	echo "Usage: php fetch_team.php <LEAGUE> <TEAMID> <YEAR> <DIRECTORY>\n";
	echo "Leagues: NFL, NCAA \n";
	exit();
}

// parse league
//
$league = $argv[1];

// parse team id
//
$teamId = intval($argv[2]);

// parse year
//
$year = intval($argv[3]);

// parse year
//
$directory = $argv[4];

// fetch team by id
//
echo "Fetching " . $league . " team " . $teamId . " from " . $year . ".\n";
fetchTeam($league, $teamId, $year, $directory);