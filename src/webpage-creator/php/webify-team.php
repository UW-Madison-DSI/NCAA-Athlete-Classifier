<?php
/******************************************************************************\
|                                                                              |
|                               webify-team.php                                |
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

include "utilities/monk-tones.php";
include "utilities/manual-skin-tones.php";
include "utilities/player-info.php";

// templates
//
$teamTemplate = file_get_contents('../templates/team.html');
$playerTemplate = file_get_contents('../templates/player.tpl');

// athlete templates
//
$manualToneTemplate = file_get_contents('../templates/manual-skin-tone.tpl');
$colorsTemplate = file_get_contents('../templates/color-thief-analyzer.tpl');
$skinToneTemplate = file_get_contents('../templates/skin-tone-classifier.tpl');
$demographyTemplate = file_get_contents('../templates/deep-face-classifier.tpl');

//
// functions
//

function webifyAthleteDirectories($srcPath, $destPath) {
	$html = '';
	$directories = [];

	// iterate over all teams
	//
	$iterator = new DirectoryIterator($srcPath);
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
		$html .= webifyAthlete($name, $srcPath . '/' . $name, $destPath, false);
	}

	return $html;
}

function webifyAthleteImages($srcPath, $destPath) {
	$html = '';
	$images = [];

	// iterate over all files
	//
	$iterator = new DirectoryIterator($srcPath);
	foreach ($iterator as $file) {
		if ($file->isFile()) {
			$path = $file->getPath();
			$filename = $file->getFilename();
			$name = pathinfo($filename, PATHINFO_FILENAME);
			$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

			if ($extension == 'jpg') {
				$images[] = array(
					"name" => $name,
					"path" => $path
				);
			}
		}
	}

	// sort images in ascending order
	//
	asort($images);

	// iterate over images
	//
	foreach ($images as $image) {
		$name = $image['name'];
		$path = $image['path'];
		$html .= webifyAthlete($name, $srcPath, $destPath, true);
	}

	return $html;
}

function nameToId($name) {
	$names = explode('.', $name);
	if (count($names) == 0) {
		return;
	}

	$lastName = $names[1];
	$position = $names[2];
	$school = $names[3];
	$team = schoolToTeam($school);
	return $lastName . '.' . $position . '.' . $team;
}

function webifyAthlete($name, $srcPath, $destPath, $useFolders) {

	// reference colors
	//
	global $manualSkinTones;
	global $monkColors;
	global $playerInfo;

	// compose paths to files
	//
	if ($useFolders) {
		$colorsPath = $srcPath . '/Colors/' . $name . ' Colors.json';
		$skinTonePath = $srcPath . '/Skin Tones/' . $name . ' Skin Tone.json';
		$demographyPath = $srcPath . '/Demography/' . $name . ' Demography.json';
	} else {
		$colorsPath = $srcPath . '/' . $name . ' Colors.json';
		$skinTonePath = $srcPath . '/' . $name . ' Skin Tone.json';
		$demographyPath = $srcPath . '/' . $name . ' Demography.json';
	}

	// parse data
	//
	$colors = file_exists($colorsPath)? json_decode(file_get_contents($colorsPath)) : null;
	$demography = file_exists($demographyPath)? json_decode(file_get_contents($demographyPath)) : null;
	$skinTones = file_exists($skinTonePath)? json_decode(file_get_contents($skinTonePath)) : null;

	// make directory
	//
	$imagesPath = $destPath . '/images';
	if (!file_exists($imagesPath)) {
		mkdir($imagesPath);
	}

	// compute file names
	//
	$iconName = $useFolders? $name . '.jpg' : $name . '.png';
	$reportName = $name . ' Report.png';
	$croppedIconName = $name . ' Cropped.png';

	// compute file paths
	//
	if ($useFolders) {
		$srcIconPath = $srcPath . '/' . $iconName;
		$srcReportPath = $srcPath . '/Reports/' . $reportName;
		$srcCroppedIconPath = $srcPath . '/Cropped/' . $croppedIconName;
	} else {
		$srcIconPath = $srcPath . '/' . $iconName;
		$srcReportPath = $srcPath . '/' . $reportName;
		$srcCroppedIconPath = $srcPath . '/' . $croppedIconName;
	}

	$iconPath = 'images/' . $iconName;
	$reportPath = 'images/' . $reportName;
	$croppedIconPath = 'images/' . $croppedIconName;

	// copy images
	//
	if (file_exists($srcIconPath)) {
		copy($srcIconPath, $imagesPath . '/' . $iconName);
	}
	if (file_exists($srcReportPath)) {
		copy($srcReportPath, $imagesPath . '/' . $reportName);
	}
	if (file_exists($srcCroppedIconPath)) {
		copy($srcCroppedIconPath, $imagesPath . '/' . $croppedIconName);
	}

	// look up player info
	//
	$id = nameToId($name);
	$info = $id? getPlayerInfo($id) : null;

	// look up player name
	//
	if ($info) {
		$playerName = $info['first_name'] . ' ' . $info['last_name'];
	} else {
		$playerName = $name;
	}

	// look up manual skin tone
	//
	$manualSkinTone = getManualSkinTone($name);
	if ($manualSkinTone) {
		if ($manualSkinTone < 10) {
			$manualSkinTone = '0' . $manualSkinTone;
		}
		if ($manualSkinTone) {
			$manualSkinTone = 'Monk' . $manualSkinTone;
		}
		$manualSkinColor = $monkColors[$manualSkinTone] ?? '';
	}

	// compute skin tone info
	//
	if ($skinTones) {
		$skinTone = $skinTones->faces[0]->tone_label;
		$skinColor = $monkColors[$skinTone];
		$dominantColor1 = $skinTones->faces[0]->dominant_colors[0]->color ?? 'black';
		$dominantColor2 = $skinTones->faces[0]->dominant_colors[1]->color ?? 'black';
		$dominantColor3 = $skinTones->faces[0]->dominant_colors[2]->color ?? 'black';
		$dominantStrength1 = round(floatval($skinTones->faces[0]->dominant_colors[0]->percent) * 100);
		$dominantStrength2 = round(floatval($skinTones->faces[0]->dominant_colors[1]->percent) * 100);
	}

	// compute race info
	//
	if ($demography) {
		$dominantRace = $demography[0]->dominant_race;
		$asianRace = round($demography[0]->race->asian);
		$indianRace = round($demography[0]->race->indian);
		$blackRace = round($demography[0]->race->black);
		$whiteRace = round($demography[0]->race->white);
		$middleEasternRace = round($demography[0]->race->{'middle eastern'});
		$latinoHispanicRace = round($demography[0]->race->{'latino hispanic'});

		// fix issue with failure on dark faces
		//
		if ($demography[0]->face_confidence == 0) {
			$dominantRace = 'black';
		}

		// adjust wording
		//
		if ($dominantRace == 'middle eastern') {
			$dominantRace = 'mideastern';
		}
		if ($dominantRace == 'latino hispanic') {
			$dominantRace = 'latino';
		}
		$dominantRace = ucwords($dominantRace);
	}

	// format manual tone
	//
	global $manualToneTemplate;
	if ($manualToneTemplate) {
		$manualToneHtml = $manualToneTemplate;
		$manualToneHtml = str_replace('<%= manual_skin_tone %>', ($manualSkinTone ?? ''), $manualToneHtml);
		$manualToneHtml = str_replace('<%= manual_skin_color %>', ($manualSkinColor ?? 'undefined'), $manualToneHtml);
	} else {
		$manualToneHtml = '';
	}

	// format colors
	//
	global $colorsTemplate;
	if ($colors && $colorsTemplate) {
		$dominantColorsHtml = $colorsTemplate;
		$red = $colors->dominant_color[0];
		$green = $colors->dominant_color[1];
		$blue = $colors->dominant_color[2];
		$dominantColor = ('rgb(' . $red . ', ' . $green . ', ' . $blue . ')');
		$dominantColorsHtml = str_replace('<%= dominant_color %>', $dominantColor, $dominantColorsHtml);
		$dominantColorsHtml = str_replace('<%= skin_tone %>', $colors->monk_label, $dominantColorsHtml);
		$dominantColorsHtml = str_replace('<%= skin_color %>', $colors->monk_color, $dominantColorsHtml);
		$dominantColorsHtml = str_replace('<%= cropped %>', $croppedIconPath, $dominantColorsHtml);
		$dominantColorsHtml = str_replace('<%= name %>', $playerName, $dominantColorsHtml);
	} else {
		$dominantColorsHtml = '';
	}

	// format skin tones
	//
	global $skinToneTemplate;
	if ($skinTones && $skinToneTemplate) {
		$skinToneHtml = $skinToneTemplate;
		$skinToneHtml = str_replace('<%= skin_tone %>', $skinTone, $skinToneHtml);
		$skinToneHtml = str_replace('<%= skin_color %>', $skinColor, $skinToneHtml);
		$skinToneHtml = str_replace('<%= dominant_color1 %>', $dominantColor1, $skinToneHtml);
		$skinToneHtml = str_replace('<%= dominant_strength1 %>', $dominantStrength1, $skinToneHtml);
		$skinToneHtml = str_replace('<%= dominant_color2 %>', $dominantColor2, $skinToneHtml);
		$skinToneHtml = str_replace('<%= dominant_strength2 %>', $dominantStrength2, $skinToneHtml);
		$skinToneHtml = str_replace('<%= report %>', $reportPath, $skinToneHtml);
		$skinToneHtml = str_replace('<%= name %>', $playerName, $skinToneHtml);
		if (!$manualSkinTone) {
			$skinToneHtml = str_replace('manual skin-tone', 'hidden manual skin-tone', $skinToneHtml);			
		}
	} else {
		$skinToneHtml = '';
	}

	// format race info
	//
	global $demographyTemplate;
	if ($demography && $demographyTemplate) {
		$demographyHtml = $demographyTemplate;
		$demographyHtml = str_replace('<%= dominant_race %>', $dominantRace, $demographyHtml);
		$demographyHtml = str_replace('<%= asian_race %>', $asianRace, $demographyHtml);
		$demographyHtml = str_replace('<%= indian_race %>', $indianRace, $demographyHtml);
		$demographyHtml = str_replace('<%= black_race %>', $blackRace, $demographyHtml);
		$demographyHtml = str_replace('<%= white_race %>', $whiteRace, $demographyHtml);
		$demographyHtml = str_replace('<%= middle_eastern_race %>', $middleEasternRace, $demographyHtml);
		$demographyHtml = str_replace('<%= latino_hispanic_race %>', $latinoHispanicRace, $demographyHtml);
	} else {
		$demographyHtml = '';
	}

	// format web content
	//
	global $playerTemplate;
	$playerHtml = $playerTemplate;
	$playerHtml = str_replace('<%= href %>', $iconPath, $playerHtml);
	$playerHtml = str_replace('<%= icon %>', str_replace("'", "\'", $iconPath), $playerHtml);
	$playerHtml = str_replace('<%= name %>', $playerName, $playerHtml);

	// add skin tone info
	//
	if (!$useFolders) {
		$playerHtml = str_replace('<div class="manual-tone"></div>', $manualToneHtml, $playerHtml);
	}
	$playerHtml = str_replace('<div class="dominant-colors"></div>', $dominantColorsHtml, $playerHtml);
	$playerHtml = str_replace('<div class="skin-tones"></div>', $skinToneHtml, $playerHtml);
	$playerHtml = str_replace('<div class="demography"></div>', $demographyHtml, $playerHtml);

	return $playerHtml;
}

//
// main
//

// check command line arguments
//
if (count($argv) < 2) {
	echo "Usage: php webify-team.php <source-directory-name> <dest-directory-name> [-folders]";
	echo "The -folders options will expect each image to be in a separate folder.";
	exit();
}

// parse command line arguments
//
$srcPath = $argv[1];
$destPath = $argv[2];
$useFolders = ($argc > 3 && $argv[3] == '-folders');

// extract name and logo
//
$name = basename($argv[1]);
$teamLogo = $name . '.png';

// create athletes html
//
if ($useFolders) {
	$athletesHtml = webifyAthleteDirectories($srcPath, $destPath);
} else {
	$athletesHtml = webifyAthleteImages($srcPath, $destPath);
}

// create web page
//
$html = $teamTemplate;
$html = str_replace('<%= logo %>', $teamLogo, $html);
$html = str_replace('<%= title %>', $name, $html);
$html = str_replace('<%= items %>', $athletesHtml, $html);

// hide unused controls
//
if (!$useFolders) {
	$html = str_replace('class="manual-skin-tone control"', 'class="manual-skin-tone control hidden"', $html);
	/*
	$html = str_replace('class="deep-face-classifier control"', 'class="deep-face-classifier control hidden"', $html);
	$html = str_replace('class="deep-face-classifier panel"', 'class="deep-face-classifier panel hidden"', $html);
	*/
}

// create directory
//
if (!file_exists($destPath)) {
	mkdir($destPath);
}

// write web page
//
file_put_contents($destPath . '/' . 'index.html', $html);