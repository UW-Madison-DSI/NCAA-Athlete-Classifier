cd php

for directory in ../../../data/NCAA/*; do
	dest=$(echo "$directory" | sed "s/data/web/")
	dest=$(echo "$dest" | sed "s/NCAA/NCAA\/teams/")
	echo "Webifying $directory"
	php webify-team.php "$directory" "$dest"
done
