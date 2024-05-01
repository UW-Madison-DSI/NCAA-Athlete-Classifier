<?php
/******************************************************************************\
|                                                                              |
|                          fetch_league_athletes.php                           |
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

//
// methods
//

/**
 * Fetch an athlete and save it to the file system and database.
 *
 * @param  string $league - The league of the athlete to fetch.
 * @param  integer $id - The id of the athlete to fetch.
 * @param  string $athletesDir - The directory name to save athlete data.
 * @return App\Models\Athlete
 */
function fetchLeagueAthlete($league, $id, $athletesDir) {

	// create athlete
	//
	$athlete = new Athlete([
		'league' => $league,
		'id' => $id
	]);

	// fetch athlete info
	//
	$athlete->fetch();

	// find first letter of athlete's last name
	//
	$lastName = $athlete->get('lastName');
	$firstLetter = substr($lastName, 0, 1);
	if ($firstLetter == '[') {
		return;
	}

	// create letter directory, if necessary
	//
	$letterDir = ($athletesDir? $athletesDir . '/' : '') . $firstLetter;
	if (!file_exists($letterDir)) {
		mkdir($letterDir);
	}

	// skip athlete if they already exist
	//
	$fullName = $athlete->get('fullName');
	$athleteDir = $letterDir . '/' . $fullName;
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
 * @param string $athletesDir - The directory name to save athlete data.
 * @param int $start - The first athlete to save.
 */
function fetchLeagueAthletes(string $league, string $athletesDir, int $start = 0) { 

	// fetch athletes
	//
	$athletes = json_decode(file_get_contents(Athlete::baseUrl($league)));
	if (!$athletes) {
		return;
	}
	
	// show athlete metadata
	//
	echo "athletes count = " . $athletes->count . "\n";
	echo "page size = " . $athletes->pageSize . "\n";
	echo "page count = " . $athletes->pageCount . "\n";

	// iterate over athlete pages
	//
	for ($i = 0; $i < $athletes->pageCount; $i++) {
		$index = ($i * $athletes->pageSize);
		if ($index >= $start) {
			$data = json_decode(file_get_contents(Athlete::baseUrl($league) . '?page=' . $i));
			if ($data) {
				for ($j = 0; $j < count($data->items); $j++) {
					$index = ($i * $athletes->pageSize) + $j;

					// check if athlete is after start index
					//
					if ($index > $start) {
						$item = $data->items[$j];
						if ($item) {

							// parse athlete id from ref
							//
							$ref = $item->{'$ref'};
							$id = basename(substr($ref, 0, strpos($ref, "?")));

							// fetch individual athlete by id
							//
							fetchLeagueAthlete($league, $id, $athletesDir);
						}
					}
				}
			}
		}
	}
}

//
// main
//

// check command line arguments
//
if (count($argv) < 2) {
	echo "Usage: php fetch_athletes.php <LEAGUE> \n";
	echo "Leagues: NFL, NCAA \n";
	exit();
}

// parse league
//
$league = $argv[1];
$leagueDir = '../../data/' . $league;

// create new league directory
//
if (!file_exists($leagueDir)) {
	mkdir($leagueDir);
}

// create new athletes directory
//
$athletesDir = $leagueDir . '/Athletes';
if (!file_exists($athletesDir)) {
	mkdir($athletesDir);
}

// fetch all athletes in league
//
$start = 0;
echo "Fetching " . $league . " athletes.\n";
fetchLeagueAthletes($league, $athletesDir, $start);