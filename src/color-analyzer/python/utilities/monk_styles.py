################################################################################
#                                                                              #
#                                monk-styles.py                                #
#                                                                              #
################################################################################
#                                                                              #
#        This is a utility for outputting css styles matching the Monk         #
#        skin tone scale.                                                      #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

import math
from monk_tones import colors, shaded_colors

#
# functions
#

def output_styles(label, colors):
	for i in range(0, len(colors)):
		index = "0" + str(i + 1) if i < 9 else str(i + 1);
		print("." + label + index, " {");
		print("    background-color:", colors[i]);
		print("}")

#
# main
#

print("Monk colors:")
output_styles("monk", colors)

print("Modified Monk colors:")
output_styles("monk", shaded_colors)