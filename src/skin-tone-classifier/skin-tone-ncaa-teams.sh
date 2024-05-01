cd php

for directory in ../../../data/NCAA/*; do
	php skin-tone-images.php "$directory"
done
