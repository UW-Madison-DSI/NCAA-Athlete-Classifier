################################################################################
#                                                                              #
#                                  images.py                                   #
#                                                                              #
################################################################################
#                                                                              #
#        This is a set of utilities for working with images.                   #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import numpy as np
from PIL import Image

#
# image manipulation functions
#

def convert_rgba_to_rgb(filename, rgb_filename):
	rgba_image = Image.open(filename)
	rgb_image = rgba_image.convert('RGB')
	rgba_pixels = np.array(rgba_image)
	rgb_pixels = np.array(rgb_image)

	# convert rgb values to white using alpha channel
	#
	if (len(rgba_pixels[0, 0]) > 3):
		for y in range(0, rgb_image.size[0] - 1): 
			for x in range(0, rgb_image.size[1] - 1):
				if rgba_pixels[x, y][3] < 255:    # check alpha
					rgb_pixels[x, y] = (255, 255, 255)

	img = Image.fromarray(rgb_pixels, 'RGB')
	img.save(rgb_filename)

def save_image(pixels, filename):
	img = Image.fromarray(pixels, 'RGB')
	img.save(filename)