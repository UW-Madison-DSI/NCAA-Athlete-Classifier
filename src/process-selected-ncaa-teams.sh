echo "Processing Selected NCAA Teams"

cd color-analyzer
sh analyze-selected-ncaa-teams.sh
cd ..

cd color-classifier
sh color-classify-selected-ncaa-teams.sh
cd ..

cd skin-tone-classifier
sh skin-tone-selected-ncaa-teams.sh
cd ..

cd demographic-classifier
sh clasify-selected-ncaa-teams.sh
cd ..

cd data-manager
sh store-selected-ncaa-teams.sh
cd ..

cd webpage-creator
sh webify-selected-ncaa-teams.sh
cd ..