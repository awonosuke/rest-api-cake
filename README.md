# rest-api-cake
this is simple REST API with CakePHP -> snippetbox  

# License
MIT License



## End point
| Method | Pettern           | Action                                    |
|:-------|:------------------|:------------------------------------------|
| `GET`  | `/snippet/all`    | Return all snippet                        |
| `GET`  | `/snippet/:id`    | Return a specific snippet                 |
| `POST` | `/snippet/create` | Create a new snippet                      |
| `POST` | `/user/signup`    | Create a new user                         |
| `POST` | `/user/login`     | Authenticate and login the user           |
| `POST` | `/user/logout`    | Logout the user                           |
| `POST` | `user/resign`     | Resign from snippetbox                    |                                          |
| `GET`  | `/admin`          | Return all user data with user's snippets |


## Status Code

- `200`: Succeeded Processing
- `4XX`: Client Error
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `405`: Method Not Allowed
- `500`: Sever Error
- `600`: Zero Data


## JSON response format
```
{
  'code': XXX,
  'body': {inner_object}
}
```


## Progress :gorilla:
- [x] :gorilla: build environment
- [x] :gorilla: bake controller and model based on table 
- [x] :gorilla: setup for JSON response
- [x] :gorilla: prepare signup method
- [ ] :gorilla: prepare login and logout method
- [ ] :gorilla: prepare create snippet method
- [ ] :gorilla: prepare find All or A snippet method
- [ ] :gorilla: prepare authenticate method
- [ ] :gorilla: setup for JSON response when Exception thrown



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
