cd python

for directory in ../../../data/NCAA/*; do
	python3 classify_colors.py "$directory"
done
