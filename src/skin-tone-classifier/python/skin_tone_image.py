################################################################################
#                                                                              #
#                             skin_tone_image.py                               #
#                                                                              #
################################################################################
#                                                                              #
#        This is a script to classify the skin tone of an image.               #
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
import stone
import cv2
import numpy as np
import utilities.monk_tones as monk_tones
import utilities.images as images

#
# main
#

if (len(sys.argv) < 2):
	print("Usage: python3 skin_tone_image.py <source-file-name> [-folders]");
	print("The '-folders' option will place additional files in separate folders.")
	exit();

# get command line arguments
#
filename = sys.argv[1]
use_folders = len(sys.argv) > 2 and sys.argv[2] == '-folders'

# set options
#
image_type = 'color'
other_args = [
	'-l ' + ' '.join(monk_tones.labels)
]

# decompose filename
#
dirname = os.path.dirname(filename)
tuple = os.path.splitext(os.path.basename(filename))
basename = tuple[0]
extension = tuple[1]

# create directory for rgb images
#
if (use_folders):
	rgb_dirname = dirname + '/RGB';
	if (not os.path.exists(rgb_dirname)):
		os.mkdir(rgb_dirname)
else:
	rgb_dirname = dirname;

# convert image file to rgb (no alpha) version
#
rgb_filename = rgb_dirname + '/' + basename + ' (rgb).png';
images.convert_rgba_to_rgb(filename, rgb_filename)

# process the image
#
result = stone.process(rgb_filename, 
	image_type = 'color',
	tone_palette = monk_tones.shaded_colors,
	tone_labels = monk_tones.labels,
	convert_to_black_white = False,
	n_dominant_colors = 2,
	new_width = 250,
	scale = 1.05,
	min_nbrs = 5,
	min_size = (90, 90),
	threshold = 0.15,
	return_report_image = True
)

# show the report image
#
report_images = result.pop("report_images")  # obtain and remove the report image from the `result`

# get (first) report image
#
keys = report_images.keys();
key = list(keys)[0]
report_image = report_images[key];

# create directory for report images
#
if (use_folders):
	reports_dirname = dirname + '/Reports';
	if (not os.path.exists(reports_dirname)):
		os.mkdir(reports_dirname)
else:
	reports_dirname = dirname;

# save report image
#
report_filename = reports_dirname + '/' + basename + ' Report.png'
pixels = np.array(cv2.cvtColor(report_image, cv2.COLOR_BGR2RGB))
images.save_image(pixels, report_filename);

# show report image
#
# stone.show(report_image)

# create directory for report images
#
if (use_folders):
	skin_tones_dirname = dirname + '/Skin Tones';
	if (not os.path.exists(skin_tones_dirname)):
		os.mkdir(skin_tones_dirname)
else:
	skin_tones_dirname = dirname;

# find name of output file
#
skin_tones_filename = skin_tones_dirname + '/' + basename + ' Skin Tone.json'

# write skin tone analysis to file
#
if (os.path.exists(skin_tones_filename)):
	os.remove(skin_tones_filename);
outfile = open(skin_tones_filename, 'a')
outfile.write(json.dumps(result, indent=4))