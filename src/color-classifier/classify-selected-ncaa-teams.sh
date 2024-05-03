cd python

echo "Classifying Colors of Wisconsin Badgers"
python3 classify_colors.py "../../../data/NCAA (Selected)/Wisconsin Badgers" -folders

echo "Classifying Colors of Maryland Terrapins"
python3 classify_colors.py "../../../data/NCAA (Selected)/Maryland Terrapins" -folders

echo "Classifying Colors of Ohio State Buckeyes"
python3 classify_colors.py "../../../data/NCAA (Selected)/Ohio State Buckeyes" -folders

echo "Classifying Colors of Michigan Wolverines"
python3 classify_colors.py "../../../data/NCAA (Selected)/Michigan Wolverines" -folders