<?php
/******************************************************************************\
|                                                                              |
|                                fetch_teams.php                               |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for fetching, storing, and saving teams.            |
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
 * @param object $db - The database to store team data.
 * @return App\Models\Team
 */
function fetchTeam(string $league, string $id, int $year, string $teamsDir) { 

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

	// save team to storage
	//
	$teamDir = $teamsDir . '/' . $team->get('displayName');
	echo "saving team to " . $teamDir . "\n";
	$team->save($teamDir);

	return $team;
}

/**
 * Fetch all teams and save them to the file system and database.
 *
 * @param string $league - The league of the teams to fetch.
 * @param string $year - The year of the teams to fetch.
 * @param string $teamsDir - The directory name to save teams data.
 * @return App\Models\Athlete
 */
function fetchTeams(string $league, int $year, string $teamsDir) { 

	// fetch teams
	//
	switch ($league) {
		case 'NFL':
			$teams = json_decode(file_get_contents(Team::baseUrl($league, $year) . '?limit=1000'));
			break;
		case 'NCAA':
			$teams = json_decode(file_get_contents(Team::baseUrl($league, $year) . '?limit=1000'));
			break;
	}
	if (!$teams) {
		return;
	}

	// ouput team metadata
	//
	echo "teams count = " . $teams->count . "\n";

	// iterate over teams
	//
	for ($i = 0; $i < count($teams->items); $i++) {
		$item = $teams->items[$i];

		// parse team id from ref
		//
		$ref = $item->{'$ref'};
		$id = basename(substr($ref, 0, strpos($ref, "?")));

		// fetch individual team by id
		//
		fetchTeam($league, $id, $year, $teamsDir);
	}
}

//
// main
//

// check command line arguments
//
if (count($argv) < 4) {
	echo "Usage: php fetch_teams.php <LEAGUE> <YEAR> <DIRECTORY>\n";
	echo "Leagues: NFL, NCAA \n";
	exit();
}

// parse league
//
$league = $argv[1];

// parse year
//
$year = intval($argv[2]);

// parse directory
//
$directory = $argv[3];

// create new directory
//
if (!file_exists($directory)) {
	mkdir($directory);
}

// fetch all teams in league
//
echo "Fetching " . $league . " teams from " . $year . ".\n";
fetchTeams($league, $year, $directory);