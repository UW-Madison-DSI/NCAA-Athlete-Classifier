################################################################################
#                                                                              #
#                              classify_color.py                               #
#                                                                              #
################################################################################
#                                                                              #
#        This is a script to classify a color into a Monk tone.                #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import os
import sys
import json
import utilities.monk_tones as monk_tones

def classify_color(filepath):

	# open colors json file
	#
	if (not os.path.isfile(filepath)):
		print("File not found: ", filepath)
		exit()
	file = open(filepath)

	# parse json file
	#
	data = json.load(file)

	# find Monk color
	#
	dominant_color = data['dominant_color']
	monk_index = monk_tones.rgb_to_index(dominant_color)

	# compose data
	#
	data = {
		'dominant_color': dominant_color,
		'palette': data['palette'],
		'monk_color': monk_tones.colors[monk_index],
		'monk_label': monk_tones.labels[monk_index]
	}

	# write results back to file
	#
	if (os.path.exists(filepath)):
		os.remove(filepath);
	outfile = open(filepath, 'a')
	outfile.write(json.dumps(data, indent=4))

#
# main
#

if __name__ == "__main__":

	# check command line args
	#
	if (len(sys.argv) < 1):
		print("Usage: python3 classify_color.py <source-file-name>")
		exit()

	# get command line args
	#
	filepath = sys.argv[1]

	# classify color of json file
	#
	classify_color(filepath)