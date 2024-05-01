cd php

for directory in ../../../data/NCAA/*; do
	echo "Storing Athletes of $directory.\n";
	php store-athletes.php "$directory" players_data
done
