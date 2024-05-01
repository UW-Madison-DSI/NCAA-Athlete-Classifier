cd php

echo "Fetching Wisconsin Badgers"
php fetch_team.php NCAA 275 2023 '../../../data/NCAA/Wisconsin Badgers'
php fetch_team_athletes.php NCAA 275 2023 '../../../data/NCAA/Wisconsin Badgers'

echo "Fetching Maryland Terrapins"
php fetch_team.php NCAA 120 2023 '../../../data/NCAA/Maryland Terrapins'
php fetch_team_athletes.php NCAA 120 2023 '../../../data/NCAA/Maryland Terrapins'

echo "Fetching Ohio State Buckeyes"
php fetch_team.php NCAA 194 2023 '../../../data/NCAA/Ohio State Buckeyes'
php fetch_team_athletes.php NCAA 194 2023 '../../../data/NCAA/Ohio State Buckeyes'

echo "Fetching Michigan Wolverines"
php fetch_team.php NCAA 130 2023 '../../../data/NCAA/Michigan Wolverines'
php fetch_team_athletes.php NCAA 130 2023 '../../../data/NCAA/Michigan Wolverines'