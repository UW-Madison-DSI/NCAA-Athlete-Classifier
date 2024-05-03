echo "Processing Wisconsin Badgers"

cd color-analyzer
sh analyze-badgers.sh
cd ..

cd color-classifier
sh color-classify-badgers.sh
cd ..

cd skin-tone-classifier
sh skin-tone-badgers.sh
cd ..

cd demographic-classifier
sh clasify-badgers.sh
cd ..

cd data-manager
sh store-badgers.sh
cd ..

cd webpage-creator
sh webify-badgers.sh
cd ..