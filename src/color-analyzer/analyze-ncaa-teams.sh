cd php

for directory in ../../../data/NCAA/*; do
	php analyze-images.php "$directory"
done
