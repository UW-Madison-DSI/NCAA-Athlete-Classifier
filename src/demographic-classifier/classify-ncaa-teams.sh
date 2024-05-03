cd python

for directory in ../../../data/NCAA/*; do
	python3 classify_images.py "$directory"
done