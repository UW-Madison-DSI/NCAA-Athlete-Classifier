echo "Reclassifying NCAA Teams"

cd color-classifier
sh color-classify-ncaa-teams.sh
cd ..

cd data-manager
sh store-ncaa-teams.sh
sh store-cleaned-ncaa-teams.sh
sh store-coded-ncaa-teams.sh
cd ..

cd webpage-creator
sh webify-ncaa-teams.sh
cd ..