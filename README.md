# MOVIES APP

* Search and browse movies/series/episodes from omdbApi

## Instalation

* Install docker and docker-compose
* Clone the project
* Create (if not exist) .env.local file and copy all the variables from .env file.
* (Optional) Create (if not exist) .env.test.local file and copy all the variables from .env.test file.
* Fill the missing variables,database variables can be invented by you, for example `movies_app_db`.
* Set local environment - in console: `set -a`, `source .env.local`, `source .env.test.local`
* Build docker `docker-compose build`
* In console: `docker-compose up` or `docker-compose up -d`
* Build database: in console: 
`docker exec -i {your_php_container_name} php /home/wwwroot/{your_application_name}/bin/console doctrine:migrations:migrate`
* (Optional) Build database for test environment: In console: 
`docker exec -i {your_php_container_name} php /home/wwwroot/{your_application_name}/bin/console doctrine:migrations:migrate --env=test`
* Browser: `localhost:{application_port}`
* To run tests: `docker exec -it {your_php_container_name} /bin/bash` and then `cd {your_application_name}` and
 `bin/phpunit`

## IMPORTANT

* To make app work (searching/browsing movies) you need to provide valid OMDB_API_KEY
 to your .env file. (see links below) (if you run tests provide it to .env.test too)
* To make registration by facebook/google work you need to provide valid OAUTH_FACEBOOK_ID, OAUTH_FACEBOOK_SECRET,
OAUTH_GOOGLE_ID, OAUTH_GOOGLE_SECRET to your .env file. (see links below)

## LINKS

* API: http://www.omdbapi.com/
* Generate API key: http://www.omdbapi.com/apikey.aspx

* Register app on Facebook to get OAUTH_FACEBOOK_ID and OAUTH_FACEBOOK_SECRET: https://developers.facebook.com/
* Register app on Google to get OAUTH_GOOGLE_ID and OAUTH_GOOGLE_SECRET (in the authorized uri redirect identificator add:
full url to path: `user_connect_google_check` for example `http://localhost:3500/user/connect/google/check`): 
https://console.developers.google.com/apis/credentials

## PROJECT MODEL

Entities:
* User: application user - only logged users are allowed to search/browse movies
* FavouriteMovie: The user's favourite movie. Allow user to save his favourite movies to local database.
