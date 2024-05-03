cd python

echo "Analyzing Demographics of Wisconsin Badgers"
python3 classify_images.py "../../../data/NCAA (Selected)/Wisconsin Badgers" -folders

echo "Analyzing Demographics of Maryland Terrapins"
python3 classify_images.py "../../../data/NCAA (Selected)/Maryland Terrapins" -folders

echo "Analyzing Demographics of Ohio State Buckeyes"
python3 classify_images.py "../../../data/NCAA (Selected)/Ohio State Buckeyes" -folders

echo "Analyzing Demographics of Michigan Wolverines"
python3 classify_images.py "../../../data/NCAA (Selected)/Michigan Wolverines" -folders