echo "Reclassifying Selected NCAA Teams"

cd color-classifier
sh color-classify-selected-ncaa-teams.sh
cd ..

cd data-manager
sh store-selected-ncaa-teams.sh
cd ..

cd webpage-creator
sh webify-selected-ncaa-teams.sh
cd ..