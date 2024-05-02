################################################################################
#                                                                              #
#                               analyze_image.py                               #
#                                                                              #
################################################################################
#                                                                              #
#        This is a script to analyze the colors of an image.                   #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import os
import sys
import json
import numpy as np
import cv2
from colorthief import ColorThief
from PIL import Image
import utilities.monk_tones as monk_tones

#
# image manipulation functions
#

def crop_image(filename, cropped_filename, width, height, hoffset, voffset):
	image = Image.open(filename).convert('RGB')

	# compute bounding box
	#
	x1 = (image.size[0] // 2) - (width // 2) + hoffset
	y1 = (image.size[1] // 2) - (height // 2) + voffset
	x2 = x1 + width
	y2 = y1 + height

	# extract cropped pixels
	#
	image_array = np.array(image) 
	cropped_pixels = image_array[y1:y2, x1:x2]

	# write image
	#
	img = Image.fromarray(cropped_pixels, 'RGB')
	img.save(cropped_filename)

#
# main
#

if (len(sys.argv) < 2):
	print("Usage: python3 analyze_image.py <source-file-name> [-folders]");
	print("The '-folders' option will place additional files in separate folders.")
	exit();

# get command line arguments
#
filename = sys.argv[1]
use_folders = len(sys.argv) > 2 and sys.argv[2] == '-folders'

# decompose filename
#
dirname = os.path.dirname(filename)
tuple = os.path.splitext(os.path.basename(filename))
basename = tuple[0]
extension = tuple[1]

# create directory for cropped images
#
if (use_folders):
	cropped_dirname = dirname + '/Cropped';
	if (not os.path.exists(cropped_dirname)):
		os.mkdir(cropped_dirname)
else:
	cropped_dirname = dirname;

# analyze image
#
cropped_filename = cropped_dirname + '/' + basename + ' Cropped.png'
crop_image(filename, cropped_filename, 100, 100, 0, -25)
color_thief = ColorThief(cropped_filename)

# compute colors
#
dominant_color = color_thief.get_color(quality=1);
palette = color_thief.get_palette(color_count=6),

# map the dominant color to a Monk tone
#
monk_index = monk_tones.rgb_to_index(dominant_color)

# get the dominant color and palette
#
results = {
	'dominant_color': dominant_color,
	'palette': palette,
	'monk_color': monk_tones.colors[monk_index],
	'monk_label': monk_tones.labels[monk_index]
}

# create directory for json
#
if (use_folders):
	colors_dirname = dirname + '/Colors';
	if (not os.path.exists(colors_dirname)):
		os.mkdir(colors_dirname)
else:
	colors_dirname = dirname;

# find name of output file
#
outfilename = colors_dirname + '/' + basename + ' Colors.json'

# write results to a file
#
if (os.path.exists(outfilename)):
	os.remove(outfilename);
outfile = open(outfilename, 'a')
outfile.write(json.dumps(results, indent=4))