echo "Processing NCAA Teams"

cd color-analyzer
sh analyze-ncaa-teams.sh
cd ..

cd color-classifier
sh color-classify-ncaa-teams.sh
cd ..

cd skin-tone-classifier
sh skin-tone-ncaa-teams.sh
cd ..

cd demographic-classifier
sh clasify-ncaa-teams.sh
cd ..

cd data-manager
sh store-ncaa-teams.sh
cd ..

cd webpage-creator
sh webify-ncaa-teams.sh
cd ..