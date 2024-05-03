################################################################################
#                                                                              #
#                               analyze_images.py                              #
#                                                                              #
################################################################################
#                                                                              #
#        This is a script to find the dominant colors of images.               #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import os
import sys
import json
import analyze_image

#
# methods
#

def analyze_directory(dirname):
	for filename in os.listdir(dirname):
		path = os.path.join(dirname, filename)

		# check if it is a directory
		#
		if not os.path.isfile(path):
			basename = os.path.basename(path)
			print("Analyzing colors of " + basename);
			analyze_image.analyze_image(path + '/' + basename + '.png')

def analyze_images_directory(dirname):
	for filename in os.listdir(dirname):
		path = os.path.join(dirname, filename)

		# checking if it is a file
		#
		if os.path.isfile(path):
			basename = os.path.basename(path)
			if not basename.startswith('.'):
				print("Analyzing colors of " + basename);
				analyze_image.analyze_image(path, True)

#
# main
#

if __name__ == "__main__":

	# check command line args
	#
	if (len(sys.argv) < 1):
		print("Usage: python3 analyze_images.py <source-directory-name> [-folders]");
		print("The -folders option expects each image to be in a separate folder.");
		exit()

	# parse command line args
	#
	src_path = sys.argv[1]
	use_folders = len(sys.argv) > 2 and sys.argv[2] == '-folders'

	print("Analyzing images of ", src_path)

	# check whether to use separate folders for generated files
	#
	if use_folders:
		analyze_directory(src_path)
	else:
		analyze_images_directory(src_path)