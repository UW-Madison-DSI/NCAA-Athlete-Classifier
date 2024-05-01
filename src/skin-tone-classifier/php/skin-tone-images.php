<?php
/******************************************************************************\
|                                                                              |
|                             skin-tone-images.php                             |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for classifying the skin tones of images.           |
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
// methods
//

function skinToneDirectory($directory) {
	$basename = basename($directory);
	$directories = [];

	// iterate over all athletes
	//
	$iterator = new DirectoryIterator($directory);
	foreach ($iterator as $file) {
		if (!$file->isFile()) {
			$pathname = $file->getPath();
			$dirname = $file->getFilename();

			# analyze athlete directory
			#
			if ($dirname != '.' && $dirname != '..' && $dirname != $basename) {
				$path = $pathname . '/' . $dirname;
				$directories[] = array( 
					"dirname" => $dirname,
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
		skinToneAthlete($directory['path']);
	}
}

function skinToneAthlete($dirname) {
	$basename = basename($dirname);
	$iterator = new DirectoryIterator($dirname);
	foreach ($iterator as $file) {
		if ($file->isFile()) {
			$filename = $file->getFilename();
			if ($filename == $basename . '.png') {
				$path = $file->getPath() . '/' . $file->getFilename();
				echo "Analyzing skin tone of athlete " . $file->getFilename() . "\n";

				// run classifier
				//
				$command = "python3 ../python/skin_tone_image.py " . '"' . $path . '"';
				shell_exec($command);
			}
		}
	}
}

function skinToneImagesDirectory($directory) {
	$basename = basename($directory);
	$images = [];

	// iterate over all items in directory
	//
	$iterator = new DirectoryIterator($directory);
	foreach ($iterator as $file) {
		if ($file->isFile()) {
			$pathname = $file->getPath();
			$filename = $file->getFilename();

			$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if ($extension == 'jpg') {
				echo "Analyzing skin tones of " . $filename . "\n";
				$path = $pathname . '/' . $filename;

				// run analyzer
				//
				$command = "python3 ../python/skin_tone_image.py " . '"' . $path . '" -folders';
				shell_exec($command);
			}
		}
	}
}

//
// main
//

if (count($argv) < 2) {
	echo "Usage: php skin-tone-images.php <source-directory-name> [-folders]";
	echo "The -folders option expects each image to be in a separate folder.";
	exit();
}

// parse command line arguments
//
$path = $argv[1];
$useFolders = ($argc > 2 && $argv[2] == '-folders');
echo "Analyzing skin tones of " . $path . "\n";

// check whether to use separate folders for generated files
//
if ($useFolders) {
	skinToneDirectory($path);
} else if (!file_exists($path . '/Skin Tones')) {
	skinToneImagesDirectory($path);
}