################################################################################
#                                                                              #
#                              classify_image.py                               #
#                                                                              #
################################################################################
#                                                                              #
#        This is a script to classify an image into demographic categories.    #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import os
import sys
import json
import pathlib
from deepface import DeepFace

#
# functions
#

def classify_image(filename, use_folders = False):

	# decompose filename
	#
	dirname = os.path.dirname(filename)
	tuple = os.path.splitext(os.path.basename(filename))
	basename = tuple[0]
	extension = tuple[1]

	# classify image
	#
	attributes = ['race']
	demography = DeepFace.analyze(filename, attributes, enforce_detection=False)

	# create directory for rgb images
	#
	if (use_folders):
		demography_dirname = dirname + '/Demography';
		if (not os.path.exists(demography_dirname)):
			os.mkdir(demography_dirname)
	else:
		demography_dirname = dirname;

	# find name of output file
	#
	filename = demography_dirname + '/' + basename + ' Demography.json';

	# remove file if already exists
	#
	if (os.path.exists(filename)):
		os.remove(filename)

	# write demography to file
	#
	file = open(filename, 'a')
	file.write(json.dumps(demography, indent=4))

#
# main
#

if __name__ == "__main__":

	# check command line args
	#
	if (len(sys.argv) < 2):
		print("Usage: python3 classify_image.py <source-file-name> [-folders]");
		print("The '-folders' option will place additional files in separate folders.")
		exit();

	# get command line args
	#
	filename = sys.argv[1]
	use_folders = len(sys.argv) > 2 and sys.argv[2] == '-folders'

	# classify demographics of image
	#
	classify_image(filename, use_folders)