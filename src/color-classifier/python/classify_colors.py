################################################################################
#                                                                              #
#                              classify_colors.py                              #
#                                                                              #
################################################################################
#                                                                              #
#        This is a script to classify colors into a Monk tones.                #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import os
import sys
import json
import classify_color

#
# methods
#

def classify_directory(dirname):
	for filename in os.listdir(dirname):
		path = os.path.join(dirname, filename)

		# check if it is a directory
		#
		if not os.path.isfile(path):
			basename = os.path.basename(path)
			print("Classifying colors of " + basename);
			classify_color.classify_color(path + '/' + basename + ' Colors.json')


def classify_colors_directory(dirname):
	print("classifying colors directory")
	for filename in os.listdir(dirname + '/Colors'):
		path = os.path.join(dirname + '/Colors', filename)

		# check if it is a file
		#
		if os.path.isfile(path):
			basename = os.path.basename(path)
			print("Classifying colors of " + basename);
			classify_color.classify_color(path)

#
# main
#

if __name__ == "__main__":

	# check command line args
	#
	if (len(sys.argv) < 1):
		print("Usage: python3 classify_colors.py <source-directory-name> [-folders]");
		print("The -folders option expects each image to be in a separate folder.");
		exit()

	# parse command line args
	#
	src_path = sys.argv[1]
	use_folders = len(sys.argv) > 2 and sys.argv[2] == '-folders'

	print("Classifying colors of ", src_path)

	# check whether to use separate folders for generated files
	#
	if use_folders:
		classify_directory(src_path)
	elif os.path.exists(src_path + '/Colors'):
		classify_colors_directory(src_path)