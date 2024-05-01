<?php
/******************************************************************************\
|                                                                              |
|                           fetch_team_athletes.php                            |
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
include "models/team.php";

//
// methods
//

/**
 * Fetch an athlete and save it to the file system and database.
 *
 * @param  string $league - The league of the athlete to fetch.
 * @param  integer $id - The id of the athelete to fetch.
 * @param  string $athletesDir - The directory name to save athelete data.
 * @param  object $db - The database to store athlete data.
 * @return App\Models\Athlete
 */
function fetchTeamAthlete($league, $id, $athletesDir) {

	// create athlete
	//
	$athlete = new Athlete([
		'league' => $league,
		'id' => $id
	]);

	// fetch athelete info
	//
	$athlete->fetch();

	// skip athlete if they already exist
	//
	$fullName = $athlete->get('fullName');
	$athleteDir = $athletesDir . '/' . $fullName;
	if (file_exists($athleteDir)) {
		echo "skipping " . $fullName . ", already exists. \n";
		return;
	}

	// skip athlete if no headshot
	//
	if (!$athlete->has('headshot')) {
		echo "skipping " . $fullName . ", no headshot. \n";
		return;
	}

	// save athlete in file system
	//
	echo "saving athlete to " . $athleteDir . "\n";
	$athlete->save($athleteDir);

	// return new athlete
	//
	return $athlete;
}

/**
 * Fetch all athletes and save them to the file system and database.
 *
 * @param string $league - The league of the teams to fetch.
 * @param string $teamId - The id of the team to fetch athletes from.
 * @param int $year - The year of the team to fetch.
 * @param string $teamsDir - The directory name to save team data.
 * @param object $db - The database to store athelete data.
 */
function fetchTeamAthletes(string $league, string $teamId, int $year, string $directory) {

	// create team
	//
	$team = new Team([
		'league' => $league,
		'id' => $teamId,
		'year' => $year
	]);

	// fetch team athletes
	//
	$url = $team->url() . '/athletes?limit=1000';
	$athletes = json_decode(file_get_contents($url));
	if (!$athletes) {
		return;
	}

	// get team info
	//
	$team->fetch();
	$teamName = $team->get('displayName');
	if (!$teamName) {
		return;
	}

	// create team directory
	//
	if (!file_exists($directory)) {
		mkdir($directory);
	}
	
	// show athlete metadata
	//
	echo "Fetching athletes from " . $teamName . "\n";
	echo "athletes count = " . $athletes->count . "\n";

	// iterate over athletes
	//
	for ($i = 0; $i < count($athletes->items); $i++) {
		$item = $athletes->items[$i];
		if ($item) {

			// parse athlete id from ref
			//
			$ref = $item->{'$ref'};
			$id = basename(substr($ref, 0, strpos($ref, "?")));

			// fetch individual athlete by id
			//
			fetchTeamAthlete($league, $id, $directory, $db);
		}
	}
}

//
// main
//

// check command line arguments
//
if (count($argv) < 3) {
	echo "Usage: php fetch_athletes_by_team.php <LEAGUE> <TEAMID> <YEAR> <DIRECTORY>\n";
	echo "Leagues: NFL, NCAA \n";
	exit();
}

// parse league
//
$league = $argv[1];

// parse team
//
$teamId = $argv[2];

// parse year
//
$year = intval($argv[3]);

// parse directory
//
$directory = $argv[4];

// fetch all athletes in team
//
fetchTeamAthletes($league, $teamId, $year, $directory);