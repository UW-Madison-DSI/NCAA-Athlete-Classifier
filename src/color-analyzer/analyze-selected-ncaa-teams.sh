cd python

echo "Analyzing Colors of Wisconsin Badgers"
python3 analyze_images.py "../../../data/NCAA (Selected)/Wisconsin Badgers" -folders

echo "Analyzing Colors of Maryland Terrapins"
python3 analyze_images.py "../../../data/NCAA (Selected)/Maryland Terrapins" -folders

echo "Analyzing Colors of Ohio State Buckeyes"
python3 analyze_images.py "../../../data/NCAA (Selected)/Ohio State Buckeyes" -folders

echo "Analyzing Colors of Michigan Wolverines"
python3 analyze_images.py "../../../data/NCAA (Selected)/Michigan Wolverines" -folders