################################################################################
#                                                                              #
#                                 monk-tones.py                                #
#                                                                              #
################################################################################
#                                                                              #
#        This is a utility for classifying colors according to the Monk        #
#        skin tone scale.                                                      #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import math
from .color_utils import lighten, darken, whiten, blacken, hex_to_rgb

#
# variables
#

colors = [
	'#f6ede4',
	'#f3e7db',
	'#f7ead0',
	'#eadaba',
	'#d7bd96',
	'#a07e56',
	'#825c43',
	'#604134',
	'#3a312a',
	'#292420'
]

labels = [
	'Monk01',
	'Monk02',
	'Monk03',
	'Monk04',
	'Monk05',
	'Monk06',
	'Monk07',
	'Monk08',
	'Monk09',
	'Monk10'
]

def shaded(colors):
	colors2 = []
	for i in range(len(colors)):
		darkness = i / (len(colors) - 1)
		lightness = 1 - darkness

		'''
		if (i < 5):
			colors2.append(darken(colors[i], 0.25))
		else:
			colors2.append(lighten(colors[i], ((darkness - 0.5) * 2) * 0.5))
		'''

		if (i < 5):
			colors2.append(darken(colors[i], 0.25))
		else:
			colors2.append(lighten(colors[i], (darkness - 0.5)))

	return colors2

shaded_colors = shaded(colors)

#
# functions
#

def rgb_to_index(rgb, palette = shaded_colors):
	index = 0
	min_distance = 0
	for i in range(len(colors)):
		monk_rgb = hex_to_rgb(palette[i])
		distance = math.sqrt(
			math.pow(monk_rgb[0] - rgb[0], 2) +
			math.pow(monk_rgb[1] - rgb[1], 2) +
			math.pow(monk_rgb[2] - rgb[2], 2)
		)
		if (i == 0 or distance < min_distance):
			min_distance = distance
			index = i
	return index

#
# main
#
if __name__ == "__main__":
	print("monk colors = ", colors)
	print("shaded colors = ", shaded_colors)
