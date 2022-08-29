# rest-api-cake
this is simple REST API with CakePHP -> snippetbox

# License
MIT License



## End point
|Method|Pettern|Action|
|:---|:---|:---|
|`GET`|`/snippet/all`|Return all snippet|
|`GET`|`/snippet/:id`|Return a specific snippet|
|`POST`|`/snippet/create`|Create a new snippet|
|`POST`|`/user/signup`|Create a new user|
|`POST`|`/user/login`|Authenticate and login the user|
|`POST`|`/user/logout`|Logout the user|
|`GET`|`/admin`|Return all user data with user's snippets|



## For me
### how to install cakephp at first time 
```
$ docker compose up -d
$ docker exec -it PHP_CONTAINER_NAME bash

---- into PHP container ----
# pwd
/var/www/html
# composer self-update && composer create-project --prefer-dist cakephp/app:4.* .
```
