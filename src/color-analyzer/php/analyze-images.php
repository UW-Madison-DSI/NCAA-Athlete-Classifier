<?php
/******************************************************************************\
|                                                                              |
|                              analyze-images.php                              |
|                                                                              |
|******************************************************************************|
|                                                                              |
|        This is a utility for analyzing the colors of images.                 |
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

function analyzeDirectory($directory) {
	$basename = basename($directory);
	$directories = [];

	// iterate over all items in directory
	//
	$iterator = new DirectoryIterator($directory);
	foreach ($iterator as $file) {
		if (!$file->isFile()) {
			$pathname = $file->getPath();
			$dirname = $file->getFilename();

			# save directory in array
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

	// sort directories in ascending order
	//
	asort($directories);

	// iterate over directories
	//
	foreach ($directories as $directory) {
		analyzeImageDirectory($directory['path']);
	}
}

function analyzeImageDirectory($dirname) {
	$basename = basename($dirname);
	$iterator = new DirectoryIterator($dirname);
	foreach ($iterator as $file) {
		if ($file->isFile()) {
			$filename = $file->getFilename();
			if ($filename == $basename . '.png') {
				$path = $file->getPath() . '/' . $filename;
				echo "Analyzing colors of " . $filename . "\n";

				// run analyzer
				//
				$command = "python3 ../python/analyze_image.py " . '"' . $path . '"';
				shell_exec($command);
			}
		}
	}
}

function analyzeImagesDirectory($directory) {
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
				echo "Analyzing colors of " . $filename . "\n";
				$path = $pathname . '/' . $filename;

				// run analyzer
				//
				$command = "python3 ../python/analyze_image.py " . '"' . $path . '" -folders';
				shell_exec($command);
			}
		}
	}
}

//
// main
//

if (count($argv) < 2) {
	echo "Usage: php analyze-images.php <source-directory-name> [-folders]";
	echo "The -folders option expects each image to be in a separate folder.";
	exit();
}

// parse command line arguments
//
$path = $argv[1];
$useFolders = ($argc > 2 && $argv[2] == '-folders');
echo "Analyzing colors of " . $path . "\n";

// check whether to use separate folders for generated files
//
if ($useFolders) {
	analyzeDirectory($path);
} else if (!file_exists($path . '/Colors')) {
	analyzeImagesDirectory($path);
}