# MOVIES APP

* Search and browse movies/series/episodes from omdbApi

## Instalation

* Install docker and docker-compose (version 1.28.2)
* Clone the project
* Create (if not exist) .env file and copy all the variables from .env.dist file.
* Create (if not exist) .env.test file and copy all the variables from .env.test.dist file.
* Fill the missing variables, database variables can be invented by you, for example `movies_app_db`.
* Set environment - in console: `set -a`, `source .env`, `source .env.test`
* Build docker `docker-compose build`
* In console: `docker-compose up` or `docker-compose up -d`
* Build database: in console: 
`docker exec -i {your_php_container_name} php /home/wwwroot/movies/bin/console doctrine:migrations:migrate`
* (Optional) Build database for test environment: In console: 
`docker exec -i {your_php_container_name} php /home/wwwroot/movies/bin/console doctrine:migrations:migrate --env=test`
* Browser: `localhost:{application_port}`
* To run tests: `docker exec -it {your_php_container_name} /bin/bash` and then `cd movies` and
 `bin/phpunit`

## IMPORTANT

* In case of editing params in env file you need to reload source from console after you destroy containers:
 `set -a`, `source .env`, `source .env.test` !!! In case of not reload there is an error with db authorization.
 Also test database params must be set to make it work.
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
