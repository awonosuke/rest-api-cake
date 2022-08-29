# rest-api-cake
this is simple REST API with CakePHP -> snippetbox

# License
MIT License

## how to build
```
// clone this repository...

$ docker compose up -d
$ docker exec -it PHP_CONTAINER_NAME bash

into PHP container
# pwd
/var/www/html
# composer self-update && composer create-project --prefer-dist cakephp/app:4.* .
```
