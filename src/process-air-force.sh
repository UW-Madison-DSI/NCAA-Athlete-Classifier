echo "Processing Air Force"

cd color-analyzer
sh analyze-air-force.sh
cd ..

cd color-classifier
sh classify-air-force.sh
cd ..

cd skin-tone-classifier
sh skin-tone-air-force.sh
cd ..

cd demographic-classifier
sh clasify-air-force.sh
cd ..

cd data-manager
sh store-air-force.sh
cd ..

cd webpage-creator
sh webify-air-force.sh
cd ..