# movies

## Instalation

* Create (if not exist) .env.local file and copy all the variables from .env file.
* (Optional) Create (if not exist) .env.test.local file and copy all the variables from .env.test file.
* Fill the missing variables (to run registration/login by facebook/google you need to provide valid keys),
database variables can be invented by you for example movies_app_db.
* Set local environment - in console: `set -a`, `source .env.local`, `source .env.test.local`
* Build docker `docker-compose build`
4. In console: `docker-compose up` or `docker-compose up -d`
5. Build database: in console: 
`docker exec -i {your_php_container_name} php /home/wwwroot/{your_application_name}/bin/console doctrine:migrations:migrate`
6. (Optional) Build database for test environment: In console: 
`docker exec -i {your_php_container_name} php /home/wwwroot/{your_application_name}/bin/console doctrine:migrations:migrate --env=test`
7. Browser: `localhost:{application_port}`
8. To run tests: `docker exec -it {your_php_container_name} /bin/bash` and then `cd {your_application_name}` and
 `bin/phpunit`
