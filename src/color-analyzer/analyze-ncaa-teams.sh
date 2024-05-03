cd python

for directory in ../../../data/NCAA/*; do
	python3 analyze_images.py "$directory"
done
