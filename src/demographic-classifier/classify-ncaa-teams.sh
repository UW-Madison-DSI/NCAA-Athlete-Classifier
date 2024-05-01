cd php

for directory in ../../../data/NCAA/*; do
	php classify-images.php "$directory"
done