<?php
/******************************************************************************\
|                                                                              |
|                              webify-league.php                               |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility creating a web page of team athletes.               |
|                                                                              |
|        Author(s): Abe Megahed                                                |
|                                                                              |
|        This file is subject to the terms and conditions defined in           |
|        'LICENSE.txt', which is part of this source code distribution.        |
|                                                                              |
|******************************************************************************|
|  Copyright (C) 2024 Data Science Institute, Univeristy of Wisconsin-Madison  |
\******************************************************************************/

$leagueTemplate = file_get_contents('../templates/league.html');
$teamTemplate = file_get_contents('../templates/team.tpl');

//
// methods
//

function webifyLeague($directory) {
	$html = '';
	$directories = [];

	// iterate over all teams
	//
	$iterator = new DirectoryIterator($directory);
	foreach ($iterator as $file) {
		if (!$file->isFile()) {
			$path = $file->getPath();
			$name = $file->getFilename();
			if (!str_starts_with($name, '.')) {
				$directories[] = array( 
					"name" => $name,
					"path" => $path
				);
			}
		}
	}

	// sort teams in ascending order
	//
	asort($directories);

	// iterate over athlete directories
	//
	foreach ($directories as $directory) {
		$name = $directory['name'];
		$path = $directory['path'];
		$html .= webifyTeam($name, $path);
	}

	return $html;
}

function webifyTeam($team, $directory) {

	// compute substitutions
	//
	$src = 'teams/' . $team . '/' . $team . '.png';
	$href = 'teams/' . $team . '/index.html';

	// format web content
	//
	global $teamTemplate;
	$html = $teamTemplate;
	$html = str_replace('<%= src %>', str_replace("'", "\'", $src), $html);
	$html = str_replace('<%= name %>', $team, $html);
	$html = str_replace('<%= href %>', $href, $html);

	return $html;
}

//
// main
//

// check command line arguments
//
if (count($argv) < 3) {
	echo "Usage: php webify-league.php <source-directory-name> <dest-directory-name>";
	exit();
}

// parse command line arguments
//
$srcPath = $argv[1];
$destPath = $argv[2];

// find league name
//
$name = basename($srcPath);
$leagueLogo = $name . '.svg';

// create web page
//
$html = $leagueTemplate;
$html = str_replace('<%= logo %>', 'images/' . $leagueLogo, $html);
$html = str_replace('<%= title %>', $name, $html);
$html = str_replace('<%= items %>', webifyLeague($srcPath), $html);

// write html page
//
file_put_contents($destPath . '/index.html', $html);