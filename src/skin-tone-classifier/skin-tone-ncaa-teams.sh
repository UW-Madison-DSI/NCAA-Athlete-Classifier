cd python

for directory in ../../../data/NCAA/*; do
	python3 skin_tone_images.py "$directory"
done