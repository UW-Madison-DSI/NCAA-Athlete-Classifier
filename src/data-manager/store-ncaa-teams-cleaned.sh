cd php

for directory in ../../../data/NCAA/*; do
	echo "Storing Athletes of $directory.\n";
	php store-cleaned-athletes.php "$directory" players_data_cleaned
done