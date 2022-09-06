# rest-api-cake
this is simple REST API with CakePHP -> snippetbox  

# License
MIT License


## End point
| Method | Pattern            | Action                                    |
|:-------|:-------------------|:------------------------------------------|
| `GET`  | `/snippet/all`     | Return all snippet                        |
| `GET`  | `/snippet/:id`     | Return a specific snippet                 |
| `POST` | `/snippet/create`  | Create a new snippet                      |
| `POST` | `/user/signup`     | Create a new user                         |
| `POST` | `/user/login`      | Authenticate and login the user           |
| `POST` | `/user/logout`     | Logout the user                           |
| `POST` | `/user/:id/resign` | Resign from snippetbox                    |                                          |
| `GET`  | `/admin`           | Return all user data with user's snippets |


## Status Code

- `200`: Succeeded Processing
- `4XX`: Client Error
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `405`: Method Not Allowed
- `500`: Sever Error


## JSON response format


```
// default response structure
{
  "code": 200,
  "url": "https://example.com",
  "body": {
    "message": "message",
    "property": "value",
    ...
  }
}

// error response structure
{
  "code": 400~500,
  "url": "https://example.com",
  "body" {
    "message": "error message",
    "errorCount": int 0~,
    "error": {
      { error object },
      ...
    }
  }
}

// if unwraped Exception occur
{
  "code": 400~500,
  "url": "https://example.com",
  "message": "Error message"
}
```


## Progress :gorilla:
- [x] :gorilla: build environment
- [x] :gorilla: bake controller and model based on table 
- [x] :gorilla: setup for JSON response
- [x] :gorilla: prepare signup method
- [x] :gorilla: prepare create snippet method
- [x] :gorilla: prepare find All or A snippet method
- [x] :gorilla: setup for JSON response when Exception thrown
- [x] :gorilla: prepare authenticate method
- [x] :gorilla: prepare login and logout method
- [ ] :gorilla: bug fix around user authentication
- [ ] :gorilla: add column users table and prepare admin user method


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

### how to send request for API
`@local environment`
```
---- GET /snippet/all ----
$ curl -i localhost:9090/snippet/all

---- GET /snippet/:id ----
$ curl -i localhost:9090/snippet/:id

---- POST /snippet/create ----
$ curl -i -X POST -d 'user_id=userId' -d 'content=An old silent pond' -d 'expire=yyyy-MM-dd HH:mm:ss' localhost:9090/snippet/create

---- POST /user/signup ----
$ curl -i -X POST -d 'email=awonosuke@example.com' -d 'password=awonosuke' -d 'user_name=awonosuke' localhost:9090/user/signup

---- POST /user/:id/resign ----
$ curl -i -X POST localhost:9090/user/:id/resign
```