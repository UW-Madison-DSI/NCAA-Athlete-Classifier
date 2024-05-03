################################################################################
#                                                                              #
#                               color_utils.py                                 #
#                                                                              #
################################################################################
#                                                                              #
#        This is a set of utilities for managing and converting colors.        #
#                                                                              #
#        Author(s): Abe Megahed                                                #
#                                                                              #
################################################################################
#     Copyright (C) 2024, Data Science Institute, University of Wisconsin      #
################################################################################

from colormap import rgb2hex, rgb2hls, hls2rgb

#
# color functions
#

def hex_to_rgb(hex):
	 hex = hex.lstrip('#')
	 hlen = len(hex)
	 return tuple(int(hex[i:i+hlen//3], 16) for i in range(0, hlen, hlen//3))

def adjust_color_lightness(r, g, b, factor):
	h, l, s = rgb2hls(r / 255.0, g / 255.0, b / 255.0)
	l = max(min(l * factor, 1.0), 0.0)
	s = max(min(s * factor, 1.0), 0.0)
	r, g, b = hls2rgb(h, l, s)
	return rgb2hex(int(r * 255), int(g * 255), int(b * 255))

def lighten(color, factor):
	r, g, b = hex_to_rgb(color)
	return adjust_color_lightness(r, g, b, 1 + factor)

def darken(color, factor):
	r, g, b = hex_to_rgb(color)
	return adjust_color_lightness(r, g, b, 1 - factor)

def whiten(color, factor):
	r, g, b = hex_to_rgb(color)
	r = int((255 * factor) + (r * (1 - factor)))
	g = int((255 * factor) + (g * (1 - factor)))
	b = int((255 * factor) + (b * (1 - factor)))
	return rgb2hex(r, g, b)

def blacken(color, factor):
	r, g, b = hex_to_rgb(color)
	r = int((r * (1 - factor)))
	g = int((g * (1 - factor)))
	b = int((b * (1 - factor)))
	return rgb2hex(r, g, b)

def contrast(color, factor):
	r, g, b = hex_to_rgb(color)
	r = 127 - ((127 - r) * factor);
	g = 127 - ((127 - g) * factor);
	b = 127 - ((127 - b) * factor);
	return rgb2hex(int(r), int(g), int(b))

#
# main
#

if __name__ == "__main__":
	color = '#ff7f00'
	print("color = ", color)
	print("lightened color by 0.5 = ", lighten(color, 0.25))
	print("darkened color by 0.5 = ", darken(color, 0.25))
	print("blackened color by 0.5 = ", blacken(color, 0.25))
	print("whitened color by 0.5 = ", whiten(color, 0.25))
