<?php
/******************************************************************************\
|                                                                              |
|                            manual-skin-tones.php                             |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for loading manual skin tone ratings.               |
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

function parseManualSkinTones($filename) {
	$skinTones = [];
	if (($handle = fopen($filename, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$name = $data[0];
			$skinTone = $data[10];
			$skinTones[$name] = $skinTone;
		}
		fclose($handle);
	}
	return $skinTones;
}

function getManualSkinTone($name) {
	global $manualSkinTones;
	if ($manualSkinTones) {
		if (array_key_exists($name, $manualSkinTones)) {
			return $manualSkinTones[$name];
		}
	}
}

//
// variables
//

$manualSkinTones = parseManualSkinTones('../../../data/Coding/4 Universities Coding.csv');